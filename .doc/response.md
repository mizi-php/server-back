### Response

Cria respostas para a requisição atual

    $resp = new InstanceResponse;

**status**: Define o status HTTP da resposta
    
    $resp->status(?int $status): self

**content**: Define o conteúdo da resposta
    
    $resp->content(mixed $content): self

**contentType**: Altera o contentType da resposta
    
    $resp->contentType(string $contentType): self

**header**: Define uma variavel do cabeçalho da resposta
    
    $resp->header(string $name, string|int $value): self

**cache**: Define se o arquivo deve ser armazenado em cache

    $resp->cache(null|bool|int $time): self

**download**: Define se a respota deve forçar o download do conteúdo
    
    $resp->download(bool|string $download): self

**send**: Envia a resposta ao navegador do cliente
    
    $resp->send(?int $status = null): never
    
> Caso o conteúdo da resposta for um objeto que contenha o metodo **send**, o conteúdo enviado será a resposta do metodo **send** do objeto.

---

### Tipos de response

**Response**: Trata respostas de html, css, javascritp ou qualquer outro texto que não precisa de tratamento

    use Mizi\Response\InstanceResponse;
    $resp = new InstanceResponse(mixed $content = null, ?int $status = null);

**ResponseFile**: Envia arquivos do servidor como resposta

    use Mizi\Response\InstanceResponseFile;
    $resp = new InstanceResponseFile(mixed $filePath = null, ?int $status = null);

**ResponseJson**: Envia array string ou numeros em forma de Json

    use Mizi\Response\InstanceResponseJson;
    $resp = new InstanceResponseJson(mixed $content = null, ?int $status = null);

**ResponseRedirect**: Redireciona o backend para outra url

    use Mizi\Response\InstanceResponseRedirect;
    $resp = new InstanceResponseRedirect($url);
