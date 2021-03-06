<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="col-md-6">
	<?if($images):?>
		<div class="carousel slide article-slide" id="article-photo-carousel">
		  	<!-- Wrapper for slides -->
		  	<div class="carousel-inner cont-slider">
			    <?$i=0;
	            foreach ($images as $path => $value):?>
	                <?if($images = $product->get_images()):?>
                        <?if( isset($value['thumb']) AND isset($value['image']) ):?>
	                        <div class="item <?=($i == 0)?'active':''?>">
		                        <a rel="prettyPhoto[gallery]" href="<?=URL::base()?><?= $value['image']?>">             
		                            <img class="main-image" src="<?=URL::base()?><?= $value['image']?>" >
		                        </a>
	                        </div>               
                        <?endif?>   
	                <?endif?>
	            <?$i++;
	            endforeach?>
		  	</div>
		  	<!-- Indicators -->
		  	<ol class="carousel-indicators">
		  		<?$j=0;
		        foreach ($images as $path => $value):?>
			        <li class="<?=($j == 0)?'active':'item'?>" data-slide-to="<?=$j?>" data-target="#article-photo-carousel">
			            <?if($images = $product->get_images()):?>        
			                <?if( isset($value['thumb']) AND isset($value['image']) ):?>
			                    <img src="<?=URL::base()?><?= $value['thumb']?>" >
			                <?endif?>   
			            <?endif?>
			        </li>
		        <?$j++;
		        endforeach?>
		  	</ol>
		</div>
	<?else:?>
		<img src="http://www.placehold.it/300x300&text=No Image">
	<?endif?>
</div>

<div class="col-md-6">

	<?if ($product->has_offer()):?>
	    <span class="offer">
	    	<h4><span class="label label-success">
	    		<i class="glyphicon glyphicon-bullhorn"></i>
	    	</span> <?=__('Offer')?> <?=$product->formated_price()?> 
	    	<del><?=$product->price.' '.$product->currency?></del> </h4>
	    </span>
		<span class="offer-valid"><?=__('Offer valid until')?> <?=(Date::format((Controller::$coupon!==NULL)?Controller::$coupon->valid_date:$product->offer_valid))?></span>
	<?else:?>
	    <?if($product->final_price() != 0):?>
	        <h4><?=__('Price')?> : <?=$product->formated_price()?></span></h4>
	    <?else:?>
	        <h4><?=__('Free')?></h4>
	    <?endif?>
	<?endif?>

	<?if (!empty($product->url_demo)):?>
	    <?if (count($skins)>0):?>
            <div class="btn-group pull-right">
              	<a class="btn btn-warning btn-xs" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>"><?=__('Demo')?></a>
			    <button class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
			        <span class="caret"></span>
			    </button>
              	<ul class="dropdown-menu" id="menu_type">
	                <?foreach ($skins as $s):?>
	                    <li><a title="<?=$s?>" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>?skin=<?=$s?>"><?=$s?></a></li>
	                <?endforeach?>
              	</ul>
            </div>
        <?else:?>
        	<a class="btn btn-warning btn-xs pull-right" href="<?=Route::url('product-demo', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>" >
	        <i class="glyphicon glyphicon-eye-open"></i> <?=__('Demo')?></a>
        <?endif?>
	<?endif?>

	<div class="button-space-review">
	<?if ($product->final_price()>0):?>
		<div class="clearfix"></div><br>
	    <a class="btn btn-success pay-btn full-w" 
	        href="<?=Route::url('product-paypal', array('seotitle'=>$product->seotitle,'category'=>$product->category->seoname))?>">
	        <?=__('Pay with Paypal')?></a>

	    <?=$product->alternative_pay_button()?>
	<?else:?>
	    <?if (!Auth::instance()->logged_in()):?>
	    <a class="btn btn-info btn-large" data-toggle="modal" data-dismiss="modal" 
	        href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
	    <?else:?>
	    <div class="clearfix"></div><br>
	    <a class="btn btn-info btn-large full-w"
	        href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'free','id'=>$product->seotitle))?>">
	    <?endif?>
	        <?if($product->has_file()==TRUE):?>
	            <?=__('Free Download')?>
	        <?else:?>
	            <?=__('Get it for Free')?>
	        <?endif?>
	    </a>
	<?endif?>
		<div class="clearfix"></div><br>
	</div>

</div>

<div class="col-md-12">
<ul class="nav nav-tabs mb-30">
	  	<li class="active">
	  		<a href="#description" data-toggle="tab"><?=__('Description')?></a>	
	  	</li>
	  	<li><a href="#details" data-toggle="tab"><?=__('Details')?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="description">
			<?=Text::bb2html($product->description,TRUE)?>
		</div>
		<div class="tab-pane" id="details">
			<?if(core::config('product.number_of_orders')):?>
				<p><span class="glyphicon glyphicon-shopping-cart"></span> <?=$number_orders?></p>
			<?endif?>
			<p><?=__('Hits')?> : <?=$hits?></p>

		    <?if ($product->has_file()==TRUE):?>
			    <p><?=__('Product format')?> : <?=strtoupper(strrchr($product->file_name, '.'))?> <?=__('file')?> </p>
			    <p><?=__('Product size')?> : <?=round(filesize(DOCROOT.'data/'.$product->file_name)/pow(1024, 2),2)?>MB</p>
		    <?endif?>

		    <?if ($product->support_days>0):?>
		    	<p><?=__('Professional support')?> : <?=$product->support_days?> <?=__('days')?></p>
		    <?endif?>

		    <?if ($product->licenses>0):?>
		    <p><?=__('Licenses')?> : <?=$product->licenses?>  
		        <?if ($product->license_days>0):?>
		        	<?=__('valid')?> <?=$product->license_days?> <?=__('days')?>
		        <?endif?>
		    </p>
		    <?endif?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<br/>
<div class="coupon">
<?=View::factory('coupon')?>
</div>
<?=$product->related()?>
<?=$product->disqus()?>