<?php
use \packages\base;
use \packages\base\json;
use \packages\base\translator;
use \packages\userpanel;
use \themes\clipone\utility;
use \packages\userpanel\date;

use \packages\news\comment;

$this->the_header();
?>
<div class="row">
    <div class="col-md-12">
        <!-- start: BASIC PRODUCT EDIT -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i>
                <span><?php echo translator::trans("edit").' '.translator::trans("new").' #'.$this->getComment()->id; ?></span>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link tooltips" title="<?php echo translator::trans('add'); ?>" href="#product-add" data-toggle="modal" data-original-title=""><i class="fa fa-plus"></i></a>
				</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="create_form" action="<?php echo userpanel\url('news/comment/edit/'.$this->getComment()->id) ?>" method="post" enctype="multipart/form-data">
                        <div class="col-md-6">
	                        <?php $this->createField(array(
								'name' => 'name',
								'label' => translator::trans("comments.name"),
								'value' => $this->getComment()->name
							));

							$this->createField(array(
								'type' => 'email',
								'name' => 'email',
								'label' => translator::trans("comments.email"),
								'value' => $this->getComment()->email,
								'class' => 'form-control ltr'
							));
							?>
						</div>
						<div class="col-md-6">
							<?php
							$this->createField(array(
								'name' => 'status',
								'type' => 'select',
								'label' => translator::trans("comments.status"),
								'options' => array(
									array(
										'title' => translator::trans("comment.accepted"),
										'value' => comment::accepted
									),
									array(
										'title' => translator::trans("comment.pending"),
										'value' => comment::pending
									),
									array(
										'title' => translator::trans("comment.unverified"),
										'value' => comment::unverified
									)
								),
								'value' => $this->getComment()->status
							));
							$this->createField(array(
								'name' => 'date',
								'label' => translator::trans("comments.date"),
								'value' => date::format('Y/m/d H:i', $this->getComment()->date),
								'class' => 'form-control ltr'
							));
							?>
						</div>
						<div class="col-md-12">
							<?php $this->createField(array(
								'name' => 'text',
								'type' => 'textarea',
								'label' => translator::trans("comments.text"),
								'class' => 'ckeditor',
								'value' => $this->getComment()->text
							));
							?>
						</div>
						<div class="col-md-12">
			                <hr>
			                <p>
			                    <a href="<?php echo userpanel\url('news/comments'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo translator::trans('return'); ?></a>
			                    <button type="submit" class="btn btn-yellow"><i class="fa fa-check-square-o"></i> <?php echo translator::trans("update") ?></button>
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
