<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Airtable;

use YOOtheme\Arr;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\HasAuthentication;
use ZOOlanders\YOOessentials\Api\WithAuthentication;

class AirtableApi extends AbstractApi implements AirtableApiInterface, HasAuthentication
{
    use WithAuthentication;

    protected string $apiEndpoint = 'https://api.airtable.com/v0';

    public function me(): array
    {
        return $this->get('meta/whoami');
    }

    public function listBases(string $offset = ''): array
    {
        $result = $this->get('meta/bases', ['offset' => $offset]);

        return $result['bases'] ?? [];
    }

    public function listTables(string $baseId): array
    {
        $result = $this->get("meta/bases/{$baseId}/tables");

        return $result['tables'] ?? [];
    }

    public function getTable(string $baseId, string $tableId): array
    {
        $tables = $this->listTables($baseId);

        $ids = array_column($tables, 'id');
        $key = array_search($tableId, $ids);

        return $tables[$key] ?? [];
    }

    public function getRecord(string $baseId, string $tableId, string $recordId): array
    {
        return $this->get("{$baseId}/{$tableId}/{$recordId}", [
            'returnFieldsByFieldId' => true,
        ]);
    }

    public function listRecords(string $baseId, string $tableId, array $filters = []): array
    {
        $result = $this->post("{$baseId}/{$tableId}/listRecords", array_merge(
            [
                'pageSize' => 100
            ],
            Arr::pick($filters, ['maxRecords', 'view']),
            [
                'returnFieldsByFieldId' => true,
            ]
        ));

        return $result['records'] ?? [];
    }

    public function createRecords(string $baseId, string $tableId, array $data): array
    {
        $result = $this->post("{$baseId}/{$tableId}", $data);

        return $result['records'] ?? [];
    }

    public function updateRecord(string $baseId, string $tableId, string $recordId, array $data, bool $replace = false): array
    {
        if ($replace) {
            return $this->put("{$baseId}/{$tableId}/{$recordId}", $data);
        }

        return $this->patch("{$baseId}/{$tableId}/{$recordId}", $data);
    }

    public function deleteRecord(string $baseId, string $tableId, string $recordId): array
    {
        return $this->delete("{$baseId}/{$tableId}/{$recordId}");
    }

    public static function castType(string $type): string
    {
        switch ($type) {
            case 'aiText':
            case 'multilineText':
            case 'richText':
            case 'singleLineText':
                return 'text';

                break;

            case 'createdBy':
            case 'lastModifiedBy':
            case 'singleCollaborator':
                return 'user';

                break;

            case 'number':
            case 'count':
            case 'currency':
            case 'duration':
            case 'percent':
            case 'rating':
            case 'autoNumber':
                return 'number';

                break;

            case 'date':
            case 'dateTime':
            case 'createdTime':
            case 'lastModifiedTime':
                return 'date';

                break;

            case 'multipleSelects':
            case 'multipleAttachments':
            case 'multipleCollaborators':
                return 'multi';

                break;

            default:
                return $type;

                break;
        }
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();
        $headers += $this->getAuthorizationHeader();

        return $headers;
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && !isset($result['error']);

        if (!$success) {
            $code = $result['error']['code'] ?? $response->getStatusCode() ?? 400;
            $message = $result['error']['message'] ?? $response->getReasonPhrase() ?? 'Unknown Error';

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
