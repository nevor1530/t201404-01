<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="row">
    <div class="span10">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="span2">
    	<?php if (!empty($this->sideNav)):?>
	        <div class="well well-small">
	        <?php
	            $this->beginWidget('zii.widgets.CPortlet', array(
	                'title'=>'子导航',
	            ));
	            $this->widget('bootstrap.widgets.TbMenu', array(
	                'items'=>$this->sideNav,
	                'htmlOptions'=>array('class'=>'operations'),
	            ));
	            $this->endWidget();
	        ?>
	        </div><!-- sidebar -->
        <?php endif; ?>
    	<?php if (!empty($this->menu)):?>
	        <div id="sidebar" class="well well-small">
	        <?php
	            $this->beginWidget('zii.widgets.CPortlet', array(
	                'title'=>'操作',
	            ));
	            $this->widget('bootstrap.widgets.TbMenu', array(
	                'items'=>$this->menu,
	                'htmlOptions'=>array('class'=>'operations'),
	            ));
	            $this->endWidget();
	        ?>
	        </div><!-- sidebar -->
        <?php endif; ?>
    </div>
</div>
<?php $this->endContent(); ?>