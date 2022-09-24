<?php namespace Yfktn\TulisanRss\Classes;

use File;
use DateTime;
use Yfktn\Tulisan\Models\Tulisan;

/**
 * Create queue to create RSS
 * @package Yfktn\TulisanRss\Classes
 */
class QueueCreateRSS
{
    public function fire($job, $data)
    {
        $linkCreatorClass = config('yfktn.tulisanrss::linkCreatorClass');
        $link = config('yfktn.tulisanrss::host');

        $fileContents = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" .
                        "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n".
                        "\t<channel>\n".
                        "\t\t<title>" . config('yfktn.tulisanrss::title') . "</title>\n" .
                        "\t\t<link>" . $link . "</link>\n" .
                        "\t\t<description>" . config('yfktn.tulisanrss::description') . "</description>\n".
                        "\t\t<atom:link href=\"" . $link . "/rss.xml\" rel=\"self\" type=\"application/rss+xml\" />\n\n";

        $posts = $this->loadData();
        $linkCreatorClass = config('yfktn.tulisanrss::linkCreatorClass');
        foreach($posts as $post) {
            $published = \Carbon\Carbon::createFromFormat("Y-m-d", $post->tgl_tampil, "Asia/Jakarta")->startOfDay();
            $description = (bool) config('yfktn.tulisanrss::showFullPostInItemDescription', false)
                ? $post->isi 
                : $post->ringkasan_paksa;
            
            $itemLink = $linkCreatorClass::createLinkOf($post);
            
            $fileContents .= "\t\t<item>\n" .
                "\t\t\t<title>" . htmlspecialchars($post->judul, ENT_QUOTES, 'UTF-8') . "</title>\n" .
                "\t\t\t<link>" . $itemLink . "</link>\n" .
                "\t\t\t<guid>" . $itemLink . "</guid>\n" .
                "\t\t\t<pubDate>" . $published->format(DateTime::RFC2822) . "</pubDate>\n" .
                "\t\t\t<description>" . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . "</description>\n" .
                "\t\t</item>\n";
        }
        
        $fileContents .= "\t</channel>\n";
        $fileContents .= "</rss>\n";

        $file = File::put(storage_path('rss.xml'), $fileContents);

        // job setelah di jalankan harus di hapus!
        $job->delete();
    }

    protected function loadData()
    {
        return Tulisan::yangSudahDitampilkan()
        ->listDiFrontEnd(
            [
                'order' => [
                    'tgl_tampil' => 'desc',
                    'updated_at' => 'desc',
                ],
                'jumlahItemPerHalaman' => config('yfktn.tulisanrss::rssRecordsCount')
            ]
        );
    }
}