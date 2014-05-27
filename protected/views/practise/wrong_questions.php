<!-- 专项训练，真题模考，我的练习 单独的页面内容 -->
<ul class="subfunction-list">
	<li><a href="<?php echo Yii::app()->createUrl("/practise/history", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">练习历史</a></li>
    <li class="current">我的错题</li>
    <li><a href="<?php echo Yii::app()->createUrl("/practise/favorites", array('exam_bank_id'=>$this->examBankId, 'subject_id'=>$this->curSubjectId))?>">我的收藏</a></li>
</ul>

<div class="content">
	<div class="bold">共<span style="color: #00a0e9">N</span>道错题
    <div class="exam-point-tree">
    	<div class="level">
        	<div class="item">
            	<span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                <button class="pull-right button">练习</button>
                <a class="pull-right" href="#">查看题目</a>
            </div>
            <div class="sublevel">
            	<div class="level">
                    <div class="item">
                        <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                        <button class="pull-right button">练习</button>
                        <a class="pull-right" href="#">查看题目</a>
                    </div>
                    <div class="sublevel">
                        <div class="level">
                            <div class="item">
                                <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                                <button class="pull-right button">练习</button>
                                <a class="pull-right" href="#">查看题目</a>
                            </div>
                            <div class="sublevel">
                                
                            </div>
                        </div>
                        <div class="level">
                            <div class="item">
                                <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                                <button class="pull-right button">练习</button>
                                <a class="pull-right" href="#">查看题目</a>
                            </div>
                            <div class="sublevel">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="level">
                    <div class="item">
                        <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                        <button class="pull-right button">练习</button>
                        <a class="pull-right" href="#">查看题目</a>
                    </div>
                    <div class="sublevel">
                        <div class="level">
                            <div class="item">
                                <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                                <button class="pull-right button">练习</button>
                                <a class="pull-right" href="#">查看题目</a>
                            </div>
                            <div class="sublevel">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="level">
        	<div class="item">
            	<span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                <button class="pull-right button">练习</button>
                <a class="pull-right" href="#">查看题目</a>
            </div>
            <div class="sublevel">
            	<div class="level">
                    <div class="item">
                        <span class="title"><span class="bold">考点名</span><span class="font-size12">(共n道题)</span></span>
                        <button class="pull-right button">练习</button>
                        <a class="pull-right" href="#">查看题目</a>
                    </div>
                    <div class="sublevel">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
