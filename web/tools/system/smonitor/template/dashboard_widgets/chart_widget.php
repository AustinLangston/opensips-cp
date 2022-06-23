<?php
require_once(__DIR__."/../../../../admin/dashboard/template/widget/widget.php");

class chart_widget extends widget
{
    public $chart;
	public $chart_box;
    function __construct($array) {
        parent::__construct($array['panel_id'], $array['widget_title'], 4, 5, $array['widget_title']);
        $this->color = 'rgb(198,226,213)';
        $this->chart = $array['widget_chart'];
		$this->chart_box = $array['widget_box'];

        
        require_once(__DIR__."/../../../../../common/cfg_comm.php");
        session_load_from_tool("smonitor");
    }


    function get_name() {
        return "Chart widget";
    }

    function echo_content() {
        $wi = $this->id;
        echo ("<div id=".$this->id."_old>");
		echo('<br>'.$this->title);
        $this->show_chart();
        echo ("</div>");
    }

    function get_as_array() {
        return array($this->get_html(), $this->get_sizeX(), $this->get_sizeY(), $this->get_id());
    }

    public static function get_stats_options($box_id = null) {
        require_once(__DIR__."/../../lib/functions.inc.php");
        if ($box_id)
            return get_stats_list($box_id);
        else return get_stats_list_all_boxes();
    }
    
    function show_chart() {
		$_SESSION['dashboard_active'] = 1;
        require_once(__DIR__."/../../lib/functions.inc.php");
        if (substr($this->chart, 0, 5) == "Group") {
            show_widget_graphs($this->chart);
        } else
            show_graph($this->chart, $this->chart_box);
		$_SESSION['dashboard_active'] = 0;
    }

    public static function chart_box_selection($stats_list, $init) {
        $slist = json_encode($stats_list);
        echo ('
            <script>
            var slist = '.$slist.';
			var init = '.$init.'
            var box_select = document.getElementById("widget_box");
            box_select.addEventListener("change", function(){update_options();}, false);
            function update_options() {
                var box_select = document.getElementById("widget_box");
                var selected_box = box_select.value;
                var chart_select = document.getElementById("widget_chart"); 
                if (slist[selected_box])  {
                    chart_select.options.length = 0;
                    var newHtml = "";
                    slist[selected_box].forEach(element => {
                        var opt = document.createElement("option");
                        opt.value = element;
                        opt.textContent = element;
                        chart_select.appendChild(opt);
                    });
                } else {
                    chart_select.options.length = 0;
                }
            } if (init == 1) update_options();
            </script>
        ');
    }

    public static function get_boxes() {
        $boxes_names = [];
        foreach ($_SESSION['boxes'] as $box) {
            $boxes_names[] = $box['id'];
        }
        return $boxes_names;
    }

    public static function new_form($params = null) {
        if (is_null($params))
			$init = 1;
		else $init = 0;
        $stats_list = self::get_stats_options();
        form_generate_input_text("Title", "", "widget_title", "n", $params['widget_title'], 20,null);
        form_generate_select("Chart", "", "widget_chart", null,  $params['widget_chart'], (!$init)?$stats_list[$params['widget_box']]:$stats_list[0]);
        form_generate_select("Box", "", "widget_box", null,  $params['widget_box'], self::get_boxes());
        self::chart_box_selection($stats_list, $init);
    }
}