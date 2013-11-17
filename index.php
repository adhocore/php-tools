<meta charset="utf-8"/> <!-- charset utf-8 is needed for output at line 22 -->
<h3>usage</h3>
<pre><code>
&lt;?php
require_once './autoload.php';
echo 'Demo:&lt;br/>',
    $n = new \Tools\Number\ToWords(123456789, 'Devanagari'), ' << Devanagari', '&lt;br/>',
    $n->setSystem('English'), ' << English', '&lt;br/>
    $d = new Tools\String\ToDevanagari('mero naam jitendra adhikaarii ho.'), ' << as unicode', '&lt;br/>',
    $d->setHtmlEntity(), ' << as html entity', '&lt;br/>',
    $s = new \Tools\Date\ToString('2011-08-08'), ' << past', '&lt;br/>',
    $s->setDate('2018-08-08'), ' << future', '&lt;br/>'
 ;
?>
</code></pre><hr/>
<h3>output</h3>
<?php
require_once './autoload.php';    
echo 'Demo:<br/>',
    $n = new \Tools\Number\ToWords(123456789, 'Devanagari'), ' << Devanagari', '<br/>',
    $n->setSystem('English'), ' << English', '<br/>',        
    $d = new Tools\String\ToDevanagari('mero naam jitendra adhikaarii ho.'), ' << as unicode', '<br/>',
    $d->setHtmlEntity(), ' << as html entity', '<br/>',        
    $s = new \Tools\Date\ToString('2011-08-08'), ' << past', '<br/>',
    $s->setDate('2018-08-08'), ' << future', '<br/>'
;
?>

