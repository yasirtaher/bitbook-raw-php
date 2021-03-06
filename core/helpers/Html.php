<?php

/**
 *
 * @author: Y T
 *
 * $tag: the tag being created
 * $attributes: an array of attributes: id, class, href, title, text, alt, target etc.
 * $content: can be null, a string or tag object
 * $selfclosers: tags that don't require a closing link
 */
class Html
{
    public static function tag($tagName, $content = '', $attributes = [])
    {
        $string = '';
        if (!empty($tagName)) {
            $string .= '<' . $tagName;

            $string .= self::checkAttributes($attributes);

            //checks if tag is selfCloser
            $string .= self::checkTagSelfCloser($tagName, $content);
        }
        return $string;
    }

    public static function div($content = '', $attributes)
    {
        return self::tag('div', $content, $attributes);
    }

    public static function span($content = '', $attributes)
    {
        return self::tag('span', $content, $attributes);
    }

    /**
     * @param array|string $url the parameter to be used to generate a valid URL
     * @param bool|string $scheme the URI scheme to use in the generated URL:
     *
     * - string: generating an absolute URL with the specified scheme (either `http` or `https`).
     *
     * @param string $content link's text
     * @param array $attributes attributes
     * @return string the generated URL
     */
    public static function a($url = '', $content = '', $attributes)
    {
        if ($url !== null) {
            $attributes['href'] = static::urlTo($url);
        }
        return static::tag('a', $content, $attributes);
    }

    public static function urlTo($url)
    {
        return strstr($url, '://') ? $url : static::getBaseUrl() . $url;
    }


//Input tags
    public static function input($type, $attributes = [])
    {
        $attributes['type'] = $type;
        return static::tag('input', '', $attributes);
    }

    public static function textInput($attributes = [])
    {
        return self::input('text', $attributes);
    }

    public static function passwordInput($attributes = [])
    {
        return self::input('password', $attributes);
    }

    public static function textarea($content = '', $attributes = [])
    {
        return self::tag('textarea', $content, $attributes);
    }

//    forms
    public static function beginForm($action = '', $method = 'post', $attributes = [])
    {
        $action = static::urlTo($action);
        $attributes['action'] = $action;
        $attributes['method'] = $method;
        $form = static::tag('form', '', $attributes);
        return $form;
    }

    public static function endForm()
    {
        return '</form>';
    }


    public static function script($src, $attributes = [], $defer = true)
    {
        $attributes['type'] = 'text/javascript';
        $attributes['src'] = static::urlTo($src);
        if ($defer) {
            $attributes['defer'] = 'defer';
        }
        return self::tag('script', '', $attributes);
    }

    public static function stylesheet($src, $attributes = [])
    {
        $attributes['type'] = 'text/css';
        $attributes['href'] = static::urlTo($src);
        $attributes['rel'] = 'stylesheet';
        return self::tag('link', '', $attributes);
    }

//    --------------------------------------------------
    public static function checkAttributes($attributes)
    {
        $string = '';
        if (!is_null($attributes)) {
            $string .= ' ' . (self::getAttributes($attributes));
        }
        return $string;
    }

    public static function checkTagSelfCloser($tagName, $content = '')
    {
        $selfClosers = array('area',
            'base',
            'br',
            'col',
            'command',
            'embed',
            'hr',
            'img',
            'input',
            'keygen',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr');
        $string = '';
        //checking if tag is selfCloser
        if (in_array($tagName, $selfClosers)) {
            $string .= '/>';
        } elseif ($tagName === "form") {
            $string .= '>';
        } else {
            $string .= '>' . $content . '</' . $tagName . '>';
        }
        return $string;
    }

    public static function getAttributes($attributes)
    {
        $string = '';
        foreach ($attributes as $key => $value) {
            if (!empty($value)) {
                $string .= ' ' . $key . '="' . $value . '"';
            } else {
                $string .= ' ' . $key;
            }
        }
        return substr($string, 1);
    }

    public static function getBaseUrl()
    {
        // output: /myproject/index.php
        $currentPath = $_SERVER['PHP_SELF'];

        // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
        $pathInfo = pathinfo($currentPath);

        // output: localhost
        $hostName = $_SERVER['HTTP_HOST'];

        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';

        // return: http://localhost/myproject/
//        return $protocol . $hostName . $pathInfo['dirname'] . "/";
        return $protocol . $hostName . "/";
    }

//    private function getContent()
//    {
//        $string = '';
//        if (!is_null($this->content)) {
//            if (is_object($this->content) && get_class($this->content) == get_class($this)) {
//                $string .= $this->checkTag($this->content);
//            } else {
//                $string .= $this->content . CHR(13) . CHR(10) . CHR(9);
//            }
//        }
//        return $string;
//    }

}