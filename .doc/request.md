### Request

Gerencia a requisição atual

    use Mizi/Request

---

**method**: Retorna o metodo da requisição atual

    Request::method(): string

---

**relativeMethod**: Retorna o metodo relativo da requisição atual

    Request::relativeMethod(): string

---

**header**: Retorna um ou todos os cabeçalhos da requisição atual

    Request::header(): mixed

---

**ssl**: Verifica se a requisição atual utiliza HTTPS

    Request::ssl(): bool

---

**host**: Retorna o host usado na requisição atual

    Request::host(): string

---

**path**: Retorna um ou todos os caminhos da URI da requisição atual

    Request::path(): mixed

---

**lockPath**: Retorna um ou todos os caminhos protegidos da URI na requisição atual

    Request::lockPath(): mixed

---

**query**: Retorna um ou todos os dados passados na QUERY GET da requisição atual

    Request::query(): mixed

---

**data**: Retorna um ou todos os dados enviados no corpo da requisição atual

    Request::data(): mixed

---

**file**: Retorna um o todos os arquivos enviados junto da requisição

    Request::file(): mixed
