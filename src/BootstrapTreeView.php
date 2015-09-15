<?php

    namespace SamIT\Yii1\Widgets;;

    use \CHtml;
class BootstrapTreeView extends \CTreeView {
    
    public $checkedIcon = 'glyphicon glyphicon-check';
    public $collapseIcon = 'glyphicon glyphicon-minus';

    public $emptyIcon = 'glyphicon';
    public $expandIcon = 'glyphicon glyphicon-plus';
    public $nodeIcon = 'glyphicon glyphicon-stop';
    public $backColor = '#FFFFFF';
    public $color = '#000000';
    public $enableLinks = true;
    public $showTags = true;
    
    /**
     * Initializes the widget.
     * This method registers all needed client scripts and renders
     * the tree view content.
     */
    public function init()
    {
        $bowerDir = \Yii::app()->params['bower-asset'];
        /** @var CClientScript $cs */
        $cs = \Yii::app()->getClientScript();
        $cs->registerScriptFile("$bowerDir/bootstrap-treeview/dist/bootstrap-treeview.min.js");
        $cs->registerCssFile("$bowerDir/bootstrap-treeview/dist/bootstrap-treeview.min.css");

        $options = array_merge($this->options, [
            'data' => $this->prepareData($this->data),
            'checkedIcon' => $this->checkedIcon,
            'collapseIcon' => $this->collapseIcon,
            'emptyIcon' => $this->emptyIcon,
            'expandIcon' => $this->expandIcon,
            'nodeIcon' => $this->nodeIcon,
            'backColor' => $this->backColor,
            'color' => $this->color,
            'enableLinks' => $this->enableLinks,
            'showTags' => $this->showTags

        ]);

        if(isset($this->htmlOptions['id'])) {
            $id = $this->htmlOptions['id'];
        } else {
            $id = $this->htmlOptions['id'] = $this->getId();
        }

        $json = json_encode($options, JSON_PRETTY_PRINT);
        $cs->registerScript("bstreeview#$id", "$('#$id').treeview($json);");
        echo \CHtml::openTag('div', $this->htmlOptions);

    }

    /**
     * Turns data from Yii CTreeView format into bootstrap-treeview format.
     */
    protected function prepareData($data, array $parent = []) {
        $result = [];
        foreach($data as $node) {
            $resultNode = [
                'text' => $node['text'],
                'nodes' => isset($node['children']) ? $this->prepareData($node['children']) : null,
                'icon' => isset($node['icon']) ? "glyphicon glyphicon-{$node['icon']}" : null,
//                'color' => null,
//                'backColor' => isset($node['active']) && $node['active'] ? '#FF0000' : null,
                'href' => isset($node['url']) ? \CHtml::normalizeUrl($node['url']) : null,
                'selectable' => false,
                'state' => [
                    'checked' => true,
//                    'disabled' => false,
                    'expanded' => isset($node['expanded']) ? $node['expanded'] : true,
                    'selected' => isset($node['active']) && $node['active'] ? $node['active'] : false,
                ],
                'tags' => isset($node['tags']) ? $node['tags'] : null
            ];
            $result[] = $resultNode;
        }
        return $result;
    }
    /**
     * Ends running the widget.
     */
    public function run()
    {
        echo CHtml::closeTag('div');

    }

}
