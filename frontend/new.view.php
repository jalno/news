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
                <i class="fa fa-newspaper-o"></i>
                <?php echo $this->new->title; ?>
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
				</div>
            </div>
            <div class="panel-body">
				<?php echo $this->getnew()->content ?>
			</div>
			<div class="panel-footer"><?php echo translator::trans('news.date').':'.date::format("l j F Y", $this->getnew()->date); ?></div>
        </div>
    </div>
</div>
<!-- end: BASIC PRODUCT EDIT -->
<?php $this->the_footer();
