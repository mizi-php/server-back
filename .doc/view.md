### View

Controla a criação de HTML CSS e JS

    use Mizi/View

---

    View::render(string $view,array|string $prepare): string;

### Estrutura
A pasta dedicada as views do projeto se encontra em **source/view**
Uma **view**, é uma pasta que contem os arquivos para montar uma visualiação
Estes arquivos são:

 - **nomeDaView.content.php**, **nomeDaView.content.html**, **content.php** ou **content.html:** HTML da view
 - **nomeDaView.script.js** ou **script.js:** Javascript da view
 - **nomeDaView.style.css** ou **style.css:** Folha de estilo da view
 - **nomeDaView.data.php**, **nomeDaView.data.json**, **data.php** ou **data.json:** Dados padrão para o prepare da view

> Todos estes arquivos são opcionais. Devem existir somente se hover comforme a nescessidade

### Diretório de view
Você pode organizar as views em diretórios. Neste casso, não é preciso informar o nome da view nos arquivos

    /source/view/nomeDaView
     - content.html 
     - data.json
     - script.js
     - style.css

### Funcionamento
Ao chamar uma view, basicamente está executando um **prepare** nos arquivos.
A classe se encarrega de montar a view da melhor forma possivel 

### Helpers da view
Existe uma forma de adicionar Helper ou resposta a prepare especificos para view. Estas adições estarão disponives em **todas as views** chamada.
Para adicionar uma opção deve-se utilizar o metodo estatico abaixo

    View::prepare(string $name, mixed $response): void

