<?php
use \packages\base;
use \packages\base\json;
use \packages\base\translator;
use \packages\userpanel;
use \themes\clipone\utility;
use \packages\userpanel\date;

use \packages\news\newpost;

$this->the_header();
?>
<div class="row">
    <div class="col-md-12">
        <!-- start: BASIC PRODUCT EDIT -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i>
                <span><?php echo translator::trans("edit").' '.translator::trans("new").' #'.$this->getNew()->id; ?></span>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <form class="create_form" action="<?php echo userpanel\url('news/edit/'.$this->getNew()->id) ?>" method="post" enctype="multipart/form-data">
                        <div class="col-md-6">
	                        <?php $this->createField(array(
								'name' => 'title',
								'label' => translator::trans("news.title"),
								'value' => $this->getNew()->title
							));
							?>
							<div class="form-group">
								<label class="control-label"><?php echo translator::trans("news.image"); ?></label>
								<div class="center news-image-box">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="news-image">
											<div class="fileupload-new thumbnail">
										        <img src="<?php echo $this->getImage($this->getNew()); ?>" alt="newsImage">
										    </div>
											<div class="fileupload-preview fileupload-exists thumbnail"></div>
										    <div class="news-image-buttons">
										        <span class="btn btn-teal btn-file btn-sm">
											        <span class="fileupload-new">
											        	<i class="fa fa-pencil"></i>
										            </span>
											        <span class="fileupload-exists">
											            <i class="fa fa-pencil"></i>
								                  	</span>
											        <input name="image" type="file">
										        </span>
										        <a href="#" class="btn fileupload-exists btn-bricky btn-sm" data-dismiss="fileupload">
										            <i class="fa fa-times"></i>
										        </a>
										    </div>
										</div>
									</div>
								</div>
							</div>
                        </div>
						<div class="col-md-6">
							<input type="hidden" name="author" value="<?php echo $this->getNew()->author->id; ?>">
							<?php $this->createField(array(
								'name' => 'author_name',
								'label' => translator::trans("news.author"),
								'value' => $this->getNew()->author->name.' '.$this->getNew()->author->lastname
							));
							$this->createField(array(
								'name' => 'date',
								'label' => translator::trans("news.date"),
								'value' => date::format('Y/m/d H:i', $this->getNew()->date),
								'class' => 'form-control ltr'
							));
							$this->createField(array(
								'name' => 'status',
								'type' => 'select',
								'label' => translator::trans("comments.status"),
								'options' => array(
									array(
										'title' => translator::trans("new.published"),
										'value' => newpost::published
									),
									array(
										'title' => translator::trans("new.unpublished"),
										'value' => newpost::unpublished
									)
								),
								'value' => $this->getNew()->status
							));
							$this->createField(array(
								'type' => 'textarea',
								'name' => 'description',
								'label' => translator::trans("news.description"),
								'value' => $this->getNew()->description,
								'rows' => 4
							));
							?>
						</div>
						<div class="col-md-12">
							<?php $this->createField(array(
								'name' => 'text',
								'type' => 'textarea',
								'label' => translator::trans("news.text"),
								'class' => 'ckeditor',
								'value' => $this->getNew()->content
							));
							?>
						</div>
						<div class="col-md-12">
			                <hr>
			                <p>
			                    <a href="<?php echo userpanel\url('news'); ?>" class="btn btn-light-grey"><i class="fa fa-chevron-circle-right"></i> <?php echo translator::trans('return'); ?></a>
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
