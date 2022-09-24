## RSS Feeder for Tulisan

This is RSS Feeder for posts that created with Tulisan plugin [Yfktn.Tulisan](https://gitlab.com/yfktn/tulisan).

To use it we need to customize our config value of `defaultLinkCreatorPage`, so link of items in channel rendered correctly.
Of course, don't forget to customize `title`, `host` and the other value.

### Customize How Link Rendered

If you need to change how link value rendered, then you need to create a class that implement `\Yfktn\TulisanRSS\Classes\ILinkCreator`. After that, it only take a second to change `linkCreatorClass` into your class path. You may check `KontenKaltengLinkCreator.php` as the example.

### We Use Queue

This plugin use queue to render `rss.xml` after Tulisan model created or saved. The reason is simple, the rendering process takes time and it would be better if the process is run in background using the Queue mechanism.

#### Run Queue in Shared Host

To make it run on a shared host we need to use the Queue `database` driver and write the command to run the Queue using crontab.

```
*/5 * * * * ~/domain/web/path/artisan queue:work --stop-when-empty >> /dev/null 2>&1
```
