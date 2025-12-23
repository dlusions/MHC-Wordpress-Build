<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Http;

use function YOOtheme\app;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Repository;

class FormSubmissionResponse
{
    protected FormSubmissionRequest $submission;

    protected Response $response;

    protected Repository $data;

    protected Repository $errors;

    protected Repository $validation;

    protected string $referrer = '';

    public function __construct(FormSubmissionRequest $submission, Response $response)
    {
        $this->submission = $submission;
        $this->response = $response;
        $this->data = new Repository();
        $this->errors = new Repository();
        $this->validation = new Repository();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): self
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);

        return $this;
    }

    public function withJson($data, ?int $status = null): self
    {
        $this->response = $this->response->withJson($data, $status);

        return $this;
    }

    public function withRedirect(string $url, int $status = 302): self
    {
        $this->response = $this->response->withRedirect($url, $status);

        return $this;
    }

    public function withData($data): self
    {
        $this->data->add('', $data);

        return $this;
    }

    public function withDataLog(array $data): self
    {
        if (app()->config->get('app.isCustomizer') || app()->config->get('app.isAdmin')) {
            $this->data->add('datalog', $data);
        }

        return $this;
    }

    public function withValidationErrors($errors): self
    {
        $this->validation->add('', $errors);

        return $this;
    }

    public function withErrors($errors): self
    {
        $this->errors->add('', $errors);

        return $this;
    }

    public function getData(): Repository
    {
        return $this->data;
    }

    public function setData($data): self
    {
        $this->data->set('', $data);

        return $this;
    }

    public function clearData(): self
    {
        $this->data = new Repository();

        return $this;
    }

    public function clearErrors(): self
    {
        $this->errors = new Repository();

        return $this;
    }

    public function clearValidationErrors(): self
    {
        $this->validation = new Repository();

        return $this;
    }

    public function cleanAllErrors(): self
    {
        return $this->clearErrors()->clearValidationErrors();
    }

    public function submission(): FormSubmissionRequest
    {
        return $this->submission;
    }

    public function hasErrors(): bool
    {
        if (
            !$this->submission()
                ->validator()
                ->validate()
        ) {
            return true;
        }

        return count($this->errors->values() + $this->validation->values()) > 0;
    }

    public function respond(): Response
    {
        if ($this->hasErrors()) {
            return $this->errorResponse();
        }

        return $this->successResponse();
    }

    protected function successResponse(): Response
    {
        if ($this->submission()->isAjax()) {
            $this->withStatus(200);
            $this->withData([
                'success' => true,
            ]);

            return $this->response->withJson($this->data->values());
        }

        return $this->response;
    }

    protected function errorResponse(): Response
    {
        $this->withValidationErrors(
            $this->submission()
                ->validator()
                ->errors()
        );

        if ($this->submission()->isAjax()) {
            return $this->response->withStatus(422, 'Invalid submitted data.')->withJson([
                'success' => false,
                'validation' => $this->validation->values(),
                'errors' => $this->errors->values(),
                'datalog' => $this->data->get('datalog') ?? []
            ]);
        }

        $headers = $this->submission()
            ->request()
            ->getHeader('Referer');
        $referrer = array_pop($headers);

        if ($this->submission()->form() !== null) {
            $referrer .=
                '#form-' .
                $this->submission()
                    ->form()
                    ->id();
        }

        return $this->response->withRedirect($referrer);
    }
}
