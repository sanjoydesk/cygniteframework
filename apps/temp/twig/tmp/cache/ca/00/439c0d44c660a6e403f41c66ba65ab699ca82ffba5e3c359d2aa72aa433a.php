<?php

/* layout/main/base.html.twig */
class __TwigTemplate_ca00439c0d44c660a6e403f41c66ba65ab699ca82ffba5e3c359d2aa72aa433a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
            'javascripts' => array($this, 'block_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
    <head>

        <title>";
        // line 4
        $this->displayBlock('title', $context, $blocks);
        echo "</title>

        ";
        // line 6
        $this->displayBlock('head', $context, $blocks);
        // line 13
        echo "

    </head>
    <body>

    ";
        // line 18
        $this->displayBlock('header', $context, $blocks);
        // line 19
        echo "
        <div class='container'>
            ";
        // line 21
        $this->displayBlock('content', $context, $blocks);
        // line 22
        echo "        </div>

    ";
        // line 24
        $this->displayBlock('footer', $context, $blocks);
        // line 25
        echo "
    ";
        // line 26
        $this->displayBlock('javascripts', $context, $blocks);
        // line 36
        echo "
    <style>
        tr:hover { background-color: #4DC7EB !important; }
    </style>

    </body>
</html>";
    }

    // line 4
    public function block_title($context, array $blocks = array())
    {
        echo " ";
    }

    // line 6
    public function block_head($context, array $blocks = array())
    {
        // line 7
        echo "            <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/css/cygnite/table.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/twitter/bootstrap/css/bootstrap-theme.min.css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/twitter/bootstrap/css/bootstrap.min.css\" />
            <!-- Pick a theme, load the plugin & initialize plugin -->
            <link href=\"";
        // line 11
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/js/tablesorter/css/theme.default.css\" rel=\"stylesheet\">
        ";
    }

    // line 18
    public function block_header($context, array $blocks = array())
    {
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
    }

    // line 24
    public function block_footer($context, array $blocks = array())
    {
    }

    // line 26
    public function block_javascripts($context, array $blocks = array())
    {
        // line 27
        echo "
        <script type=\"text/javascript\" src=\"";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/js/cygnite/jquery.js\" ></script>
        <script type=\"text/javascript\" src=\"";
        // line 29
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/js/custom.js\" > </script>
        <script type=\"text/javascript\" src=\"";
        // line 30
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/twitter/bootstrap/js/bootstrap.js\" ></script>
        <script type=\"text/javascript\" src=\"";
        // line 31
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/js/tablesorter/js/jquery.tablesorter.min.js\"></script>
        <link rel=\"stylesheet\" href=\"";
        // line 32
        echo twig_escape_filter($this->env, (isset($context["baseUrl"]) ? $context["baseUrl"] : null), "html", null, true);
        echo "assets/css/cygnite/style.css\" />


    ";
    }

    public function getTemplateName()
    {
        return "layout/main/base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  139 => 32,  135 => 31,  131 => 30,  127 => 29,  123 => 28,  117 => 26,  107 => 21,  87 => 8,  82 => 7,  79 => 6,  73 => 4,  63 => 36,  61 => 26,  58 => 25,  56 => 24,  52 => 22,  50 => 21,  46 => 19,  37 => 13,  35 => 6,  25 => 1,  182 => 65,  179 => 64,  171 => 59,  164 => 54,  160 => 52,  157 => 51,  137 => 45,  133 => 44,  129 => 43,  124 => 41,  120 => 27,  116 => 39,  112 => 24,  108 => 37,  105 => 36,  102 => 18,  99 => 34,  96 => 11,  93 => 32,  91 => 9,  88 => 30,  70 => 29,  68 => 28,  66 => 27,  48 => 12,  44 => 18,  41 => 8,  38 => 7,  33 => 4,  30 => 4,);
    }
}
