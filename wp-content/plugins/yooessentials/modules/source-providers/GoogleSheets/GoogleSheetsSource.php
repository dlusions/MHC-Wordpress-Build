<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleSheets;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Api\Google\GoogleSheetsApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class GoogleSheetsSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    use CachesResolvedSourceData;

    public const SHEET_COLUMN_START = 'A';
    public const SHEET_COLUMN_END = 'Z';
    public const SHEET_ROW_OFFSET = 0;
    public const SHEET_ROW_LIMIT = 20;

    public string $account;

    public string $spreadsheet;

    public string $sheetName;

    public string $startColumn = self::SHEET_COLUMN_START;

    public string $endColumn = self::SHEET_COLUMN_END;

    private ?GoogleSheetsApiInterface $api = null;

    public function bind(array $config): SourceInterface
    {
        parent::bind($config);

        $this->account = $config['account'] ?? '';
        $this->spreadsheet = $config['sheet_id'] ?? '';
        $this->sheetName = $config['sheet_name'] ?? '';
        $this->startColumn = $config['start_column'] ?? self::SHEET_COLUMN_START;
        $this->endColumn = $config['end_column'] ?? self::SHEET_COLUMN_END;

        return $this;
    }

    public function types(): array
    {
        if (!$this->auth()) {
            return [];
        }

        $objectType = new Type\GoogleSheetsSheetType($this);
        $queryType = new Type\GoogleSheetsRecordsQueryType($this, $objectType);

        return [$objectType, $queryType];
    }

    public static function getCacheKey(): string
    {
        return 'google-sheets-source';
    }

    public function defaultName(): string
    {
        return 'Sheet';
    }

    public function auth(): ?AuthOAuth
    {
        if (!$this->account) {
            throw new \Exception('Auth Account must be specified.');
        }

        return app(AuthManager::class)->auth($this->account);
    }

    public function headers(): array
    {
        $range = $this->formatRange(1, 1);

        $headers = self::resolveFromCache($this, [$range], fn () => $this->api()->values($this->spreadsheet, $range)['values'][0] ?? []);

        if (empty($headers)) {
            throw new \Exception('Missing spreadsheet headers. ' . $range);
        }

        $i = 0;

        return array_map(function ($headerCell) use (&$i) {
            $i++;
            $headerCell = trim($headerCell);
            if (strlen($headerCell) <= 0) {
                throw new \RuntimeException("Header at column position {$i} is empty. All columns must have an header!");
            }

            return $headerCell;
        }, $headers);
    }

    public function formatRange(int $offset = self::SHEET_ROW_OFFSET, int $limit = self::SHEET_ROW_LIMIT): string
    {
        $sheetName = '';
        if ($this->sheetName) {
            $sheetName = "'{$this->sheetName}'";
        }

        return "{$sheetName}!{$this->startColumn}{$offset}:{$this->endColumn}{$limit}";
    }

    public function api(): GoogleSheetsApiInterface
    {
        if ($this->api !== null) {
            return $this->api;
        }

        $auth = app(AuthManager::class)->auth($this->account);

        if (!$auth) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'google-sheets',
                'error' => 'Missing Auth',
            ]);
        }

        return $this->api = app(GoogleSheetsApiInterface::class)->withAccessToken($auth->accessToken());
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        if (!($auth = $this->auth())) {
            return HasCacheTimes::MIN_CACHE_TIME;
        }

        if ($auth instanceof AuthOAuth && $auth->custom()) {
            return 0;
        }

        return HasCacheTimes::MIN_CACHE_TIME;
    }
}
