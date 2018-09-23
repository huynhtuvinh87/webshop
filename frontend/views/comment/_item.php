<?php

use common\components\Constant;
if(!empty($model->answers) && !empty(array_count_values(array_column($model['answers'], 'status'))[2])){
    $count = array_count_values(array_column($model['answers'], 'status'))[2];
}else{
    $count = 0;
}
?>
<?php if($model->status == 2){ ?>
<li class="qna-item" id="q-<?= $model->id ?>">
    <div class="qna-item-group"> <span class="question">Q<em class="triangle"></em></span>
        <div class="qna-content"><?= $model->content ?></div>
        <div class="qna-meta"><?= $model->name ?>. - <?= Constant::time($model->created_at) ?></div>
        <p>
            <a href="javascript:void(0)" data-id="<?= $model->id ?>" data-name="@<?= $model->name ?>:" class="comment-reply"><i class="glyphicon glyphicon-share-alt"></i> Trả lời</a>
            <a href="javascript:void(0)" data-id="<?= $model->id ?>"  class="answer-all">Xem tất cả câu trả lời (<?= $count > 0 ? $count : 0 ?>)</a>
        </p>
    </div>
    <div class="list-answer">
        <?php
        if ($model['answers']) {
            $i = count($model['answers']) - 1;
            if ($model['answers'][$i]['content'] == 2) {
                ?>
                <div class="qna-item-group"> 
                    <span class="answer">A<em class="triangle"></em></span>
                    <div class="qna-content"><?= $model['answers'][$i]['content'] ?></div>
                    <div class="qna-meta"><?= $model['answers'][$i]['name'] ?> - trả lời cách đây <?= Constant::time($model['answers'][$i]['created_at']) ?></div>
                    <p><a href="javascript:void(0)" data-id="<?= $model->id ?>" data-name="@<?= $model['answers'][$i]['name'] ?>:" class="comment-reply"><i class="glyphicon glyphicon-share-alt"></i> Trả lời</a></p>
                </div>
                <?php
            }
        }
        ?>
    </div>
</li>
<?php } ?>