<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\YouTube;

use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class YouTubePlaylistSource extends YouTubeChannelSource
{
    public string $playlist;

    protected string $configFile = 'config-playlist.json';

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->account = $config['account'] ?? '';
        $this->playlist = $config['playlist_id'] ?? '';

        return $this;
    }

    public function types(): array
    {
        return [
            new Type\YouTubeVideoType(),
            new Type\YouTubePlaylistVideoQueryType($this),
            new Type\YouTubePlaylistVideosQueryType($this),
        ];
    }
}
