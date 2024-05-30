<?php
use packages\base\Translator;
use packages\news\Comment;
use packages\userpanel;
use packages\userpanel\Date;

$this->the_header();
?>
<div class="row">
    <div class="col-md-12">
        <!-- start: BASIC PRODUCT EDIT -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i>
                <span><?php echo Translator::trans('edit').' '.Translator::trans('new').' #'.$this->getComment()->id; ?></span>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="create_form" action="<?php echo userpanel\url('news/comment/edit/'.$this->getComment()->id); ?>" method="post" enctype="multipart/form-data">
                        <div class="col-md-6">
	                        <?php $this->createField([
	                            'name' => 'name',
	                            'label' => Translator::trans('comments.name'),
	                            'value' => $this->getComment()->name,
	                        ]);

$this->createField([
    'type' => 'email',
    'name' => 'email',
    'label' => Translator::trans('comments.email'),
    'value' => $this->getComment()->email,
    'class' => 'form-control ltr',
]);
?>
						</div>
						<div class="col-md-6">
							<?php
$this->createField([
    'name' => 'status',
    'type' => 'select',
    'label' => Translator::trans('comments.status'),
    'options' => [
        [
            'title' => Translator::trans('comment.accepted'),
            'value' => Comment::accepted,
        ],
        [
            'title' => Translator::trans('comment.pending'),
            'value' => Comment::pending,
        ],
        [
            'title' => Translator::trans('comment.unverified'),
            'value' => Comment::unverified,
        ],
    ],
    'value' => $this->getComment()->status,
]);
$this->createField([
    'name' => 'date',
    'label' => Translator::trans('comments.date'),
    'value' => Date::format('Y/m/d H:i', $this->getComment()->date),
    'class' => 'form-control ltr',
]);
?>
						</div>
						<div class="col-md-12">
							<?php $this->createField([
							    'name' => 'text',
							    'type' => 'textarea',
							    'label' => Translator::trans('comments.text'),
							    'class' => 'ckeditor',
							    'value' => $this->getComment()->text,
							]);
?>
						</div>
						<div class="col-md-12">
			                <hr>
			                <p>
			                    <a href="<?php echo userpanel\url('news/comments'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo Translator::trans('return'); ?></a>
			                    <button type="submit" class="btn btn-yellow"><i class="fa fa-check-square-o"></i> <?php echo Translator::trans('update'); ?></button>
			                </p>
						</div>
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end: BASIC PRODUCT EDIT -->
<?php
    $this->the_footer();
