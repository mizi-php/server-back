### Helper

**url**: Retorna uma [URL](https://github.com/mizi-php/server/tree/main/.doc/url.md)

    url(): String

**minify_html**: Minifica uma string HTML

    minify_html($input)
    
**minify_css**: Minifica uma string CSS

    minify_css($input)
    
**minify_js**: Minifica uma string Javascript

    minify_js($input)

---

**view**: Retorna a string do conteúdo de uma [view](https://github.com/mizi-php/server/tree/main/.doc/view.md)

    view(string $viewRef, array|string $prepare = []): String

**viewIn**: Retorna a string do conteúdo de uma  [view](https://github.com/mizi-php/server/tree/main/.doc/view.md) dentro da view original

    viewIn(string $viewRef, array|string $prepare = []): String