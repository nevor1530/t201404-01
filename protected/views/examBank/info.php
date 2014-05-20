<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - ' . $exam_bank_name;
$this->breadcrumbs=array(
	'Login',
);
?>

<style type="text/css">
.main {
	padding-top: 20px;
	margin: 0 auto;
}

.btn[disabled] {
	background-color: #006DCC;
	opacity: 1;
	min-width: 150px;
}

.subjects {
	margin-top: 15px;
	font-size: 0;
}

.subject {
	border-style: solid; 
	border-color: #a6cff8;
	border-width: 1px;
	padding: 10px 8px;
	font-size: 15px;
}

.active {
	background-color: #a6cff8;
	color: white;
}

.deactive {
	background-color: #FFFFFF;
}

.tabs {
	margin-top: 3px;
	font-size: 0;
}

.tab {
	border-style: solid; 
	border-color: #a6cff8;
	border-width: 1px;
	padding: 10px 5px;
	font-size: 14px;
	background-color: #d8e8fa;
	padding: 6px 30px;
}

.exam_point_tree {
	margin-top: 20px;
}

.exam_point_tree_item {
    background-color: #F5F5F5;
}


.treeview ul {
    background-color: #FFFFFF;
    margin-top: 4px;
}

.treeview, .treeview ul {
    list-style-image: none;
    list-style-position: outside;
    list-style-type: none;
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
    margin-top: 0;
    padding-bottom: 0;
    padding-left: 0;
    padding-right: 0;
    padding-top: 0;
}

.treeview .hitarea {
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: url("<?php echo Yii::app()->request->baseUrl; ?>/images/treeview-default.gif");
    background-origin: padding-box;
    background-position: -64px -25px;
    background-repeat: no-repeat;
    background-size: auto auto;
    cursor: pointer;
    float: left;
    height: 16px;
    margin-left: -16px;
    width: 16px;
}

.treeview li {
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
    margin-top: 0;
    padding-bottom: 3px;
    padding-left: 16px;
    padding-right: 0;
    padding-top: 3px;
}

.treeview li {
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: url("images/treeview-default-line.gif");
    background-origin: padding-box;
    background-repeat: no-repeat;
    background-size: auto auto;
}


.treeview li.collapsable, .treeview li.expandable {
    background-position: 0 -176px;
}
</style>

<div class="main">
	<div class="row">
		<div class="offset2">
			<button class="btn btn-primary btn-large disabled" disabled="disabled"><?php echo $exam_bank_name; ?></button>
		</div>
		<div class="offset2 subjects">
			<?php for ($i = 0; $i < count($subjects); $i++) { 
				$subject = $subjects[$i];
				$style = ($i == 0) ? "active" : "deactive";
			?>
			<button class="subject <?php echo $style; ?>"><?php echo $subject['name'];?></button>
			<?php } ?>
		</div>
		<div class="offset2 tabs">
			<button class="tab">专项训练</button>
			<button class="tab">真题模考</button>
			<button class="tab">我的练习</button>
		</div>
		<div class="offset2 exam_point_tree">
			<ul id="yw0" class="treeview">
				<li id="8" class="hasChildren collapsable">
					<div class="hitarea hasChildren-hitarea collapsable-hitarea"></div>
					<div class="exam_point_tree_item">人文</div>
					<ul>
						<li id="9" class="hasChildren collapsable">
							<div class="hitarea hasChildren-hitarea collapsable-hitarea"></div>
							<div class="exam_point_tree_item">历史</div>
						</li>
					</ul>
					<ul>
						<li id="9" class="hasChildren collapsable">
							<div class="hitarea hasChildren-hitarea collapsable-hitarea"></div>
							<div class="exam_point_tree_item">历史</div>
						</li>
					</ul>
				</li>
				<li id="8" class="hasChildren collapsable">
					<div class="hitarea hasChildren-hitarea collapsable-hitarea"></div>
					<div class="exam_point_tree_item">人文</div>
					<ul>
						<li id="9" class="hasChildren collapsable">
							<div class="hitarea hasChildren-hitarea collapsable-hitarea"></div>
							<div class="exam_point_tree_item">历史</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>