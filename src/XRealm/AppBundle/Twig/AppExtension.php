<?php
namespace XRealm\AppBundle\Twig;
use Symfony\Component\Filesystem\Filesystem;

class AppExtension extends \Twig_Extension
{

    protected $smileys = array(
        ':light:'  =>  '<i class="smiley smiley-lightbulb"></i>',
        ':king:'  =>  '<i class="smiley smiley-crown"></i>',
        ':bomb:'  =>  '<i class="smiley smiley-bomb"></i>',
        ':heart:'  =>  '<i class="smiley smiley-heart"></i>',
        ':angel:'  =>  '<i class="smiley smiley-angellist"></i>',
        ':smile:'  =>  '<i class="smiley smiley-happy"></i>',
        ':wink:'  =>  '<i class="smiley smiley-wink"></i>',
        ':sad:'  =>  '<i class="smiley smiley-unhappy"></i>',
        ':zzz:'  =>  '<i class="smiley smiley-sleep"></i>',
        ':sleep:'  =>  '<i class="smiley smiley-sleep"></i>',
        ':thumbsup:'  =>  '<i class="smiley smiley-thumbsup"></i>',
        ':thumb:'  =>  '<i class="smiley smiley-thumbsup"></i>',
        ':devil:'  =>  '<i class="smiley smiley-devil"></i>',
        ':shocked:'  =>  '<i class="smiley smiley-surprised"></i>',
        ':surprise:'  =>  '<i class="smiley smiley-surprised"></i>',
        ':surprised:'  =>  '<i class="smiley smiley-surprised"></i>',
        ':tongue:'  =>  '<i class="smiley smiley-tongue"></i>',
        ':coffee:'  =>  '<i class="smiley smiley-coffee"></i>',
        ':sunglass:'  =>  '<i class="smiley smiley-sunglasses"></i>',
        ':sunglasses:'  =>  '<i class="smiley smiley-sunglasses"></i>',
        ':displeased:'  =>  '<i class="smiley smiley-displeased"></i>',
        ':beer:'  =>  '<i class="smiley smiley-beer"></i>',
        ':grin:'  =>  '<i class="smiley smiley-grin"></i>',
        ':angry:'  =>  '<i class="smiley smiley-angry"></i>',
        ':saint:'  =>  '<i class="smiley smiley-saint"></i>',
        ':cry:'  =>  '<i class="smiley smiley-cry"></i>',
        ':shoot:'  =>  '<i class="smiley smiley-shoot"></i>',
        ':squint:'  =>  '<i class="smiley smiley-squint"></i>',
        ':laugh:'  =>  '<i class="smiley smiley-laugh"></i>',
    );

    protected $kernel;
    
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('datepattern', array($this, 'datepattern')),
            new \Twig_SimpleFilter('smileys', array($this, 'smileys')),
            new \Twig_SimpleFilter('cacheimage', array($this, 'cacheImage')),
            new \Twig_SimpleFilter('wowheadtooltip', array($this, 'wowheadTooltip')),
            new \Twig_SimpleFilter('buyprice', array($this, 'buyprice')),

        );
    }

    public function buyprice($base)
    {
        $int = $base;
        $gold = floor($int / 10000);
        $int = $int - ($gold * 10000);
        $silver = floor($int / 100);
        $bronze = $int - ($silver * 100);

        $return = '';
        if($gold)
        {
            $return .= $gold . ' <span class="currency-img gold"></span> ';
        }
        if($silver || $gold)
        {

            $return .= str_pad($silver, 2, "0", STR_PAD_LEFT) . ' <span class="currency-img silver"></span> ';
        }
        if($base)
        {
            $return .= str_pad($bronze, 2, "0", STR_PAD_LEFT) . ' <span class="currency-img copper"></span> ';
        }
        return $return;
    }

    public function datepattern(\Datetime $datetime, $pattern = 'd. MMMM Y', $lang = 'de_DE')
    {
        $formatter = new \IntlDateFormatter($lang, \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $formatter->setPattern($pattern);
        return $formatter->format($datetime);
    }

    public function smileys($string)
    {
        $string = htmlspecialchars ($string);
        $string = str_replace (array_keys($this->smileys), $this->smileys, $string);
        $matches = array();
        $values = array('upgd','item','ench','pvp','chal', 'lvl', 'gems', 'sock', 'pcs', 'rand', 'forg', 'bonus');
        $pattern = '/(\[)((' . implode('|', $values) . ')[=]+(.)+)+(\])/';
        $string = preg_replace_callback($pattern, array($this, 'addtooltip'), $string);

        return $string;
    }

    public function addtooltip($string)
    {
        if(strpos($string[0], 'item') === false)
        {
            return $string[0];
        }
        $string = str_replace(array('[',']'), '', $string[0]);
        $sub = explode('&', $string);
        $id = 0;

        $return = '<a class="activity-tooltip"';
        if($id == 0) {
            $return .= ' onclick="return false;"';
        }
        
        $return .= ' href="#" rel="';
        $return .= $string;
        $return .= '"><i class="icon icon-chain"></i> </a>';

        return $return;
    }

    public function cacheImage($url, $chachetime = 86400)
    {
        $name = md5($url);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $cachedName = $name . '.' . $ext;
        $webPath = '/images/cached/' . substr($cachedName, 0, 2);
        
        $root = $this->kernel->getRootDir() . '/../web' . $webPath;
        $imageRoot = $root . '/' . $cachedName;

        $fs = new Filesystem();
        if(!$fs->exists($root))
        {
            $fs->mkdir($root, 0700);
        }
        
        if(!file_exists($imageRoot))
        {
            $this->saveImage($url, $imageRoot);
            
        }
        else
        {
            if(time() - filemtime ($imageRoot) > $chachetime)
            {
                $this->saveImage($url, $imageRoot);
            }
        }

        return $webPath . '/' . $cachedName;
    }

    protected function saveImage($inPath,$outPath)
    {
        @copy ($inPath, $outPath);
        
    }

    public function getName()
    {
        return 'app_extension';
    }

    public function wowheadTooltip($item)
    {
        if(empty($item['id']))
        {
            return '';
        }
        $con = '&amp;';
        $rel = 'item=' . $item['id'];
        if(!empty($item['tooltipParams']))
        {
            if(!empty($item['tooltipParams']['enchant']))
            {
                $rel .= $con . 'ench=' . $item['tooltipParams']['enchant'];
            }
            if(!empty($item['tooltipParams']['set']))
            {
                $rel .= $con . 'pcs=' . implode(':', $item['tooltipParams']['set']);
            }
            if(!empty($item['tooltipParams']['gem0']))
            {
                $rel .= $con . 'gems=' . $item['tooltipParams']['gem0'];
            }
        }
        if(!empty($item['bonusLists']))
        {
            $rel .= $con . 'bonus=' . implode(':', $item['bonusLists']);
        }

        return $rel;
    }
}