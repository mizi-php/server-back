### jsonRequest

Comunicação para APIs Json

    use Mizi/JsonRequest

---
### Utilizando a classe
Para criar um objeto de requisição, crie um objeto **InstanceJsonRequest** informando o host da requisição

    use Mizi\JsonRequest\InstanceJsonRequest;

    $request = new InstanceJsonRequest('http://api.domain.com/...');

Com o objeto criado, pode-se adicionar header com o metodo **header**

    $request->header('name','value');

Para executar a requisição, utilize os metodos **get**, **post**, **put** ou **delete**

    $resp = $request->get(...$options);
    $resp = $request->post(...$options);
    $resp = $request->put(...$options);
    $resp = $request->delete(...$options);

O resultado sempre será a resposta da requisição convertida em array

> A classe funciona apenas para APIs REST com a resposta em JSON

### Opções da requisição
Ao executar uma requisição, pode-se informar opções extras

    $resp = $request->get('get','user',['id'=>1],true...);

 - **Parametros string** são tratados como rotas da requisição

 - **Parametros array**: são tratados como corpo da requisição

 - **Parametros boolean**: define se a classe deve ou não utilizar o **RELATIVE_METHOD**

### Relative Method
O metodo relativo transforma a requisição em **POST**, tambem adiciona um campo ao corpo da requisição de nome **_method** que contem o tipo real da requisição.

Para utilizar o metodo relativo, basta informar **true** nas opções, ou utilizar o metodo **relativeMethod**

    $resp = $request->get(true);
    OU
    $rep->relativeMethod(true);

O metodo relativo é extremamete util caso precise enviar arquivos para a API. 

> Para que funcione, a API deve estar preparada para receber metodos relativos

### Enviando arquivos 
Para enviar um arquivo junto da requisição basta adicionar o caminho do arquivo com o metodo **file**

    $resp->file('library/assets/favicon.ico');

> Os arquivos so serão enviados em requisições **POST** ou que utilizem o **RELATIVE_METHOD**
