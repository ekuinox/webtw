# twitter.comをスクレイピングするやつ  

ゴミ  

てきとうにして
```php:sample.php
<?php

require 'webtw.php';

$wt = new webtw(USERNAME, PASSWORD);

$wt->post('https://twitter.com/i/tweet/create', ['status' => 'test tweet']);
```

こんな感じで
