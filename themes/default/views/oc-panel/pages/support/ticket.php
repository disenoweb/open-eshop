<?php defined('SYSPATH') or die('No direct script access.');?>


	<div class="page-header">
        <h1><?=$ticket->title?></h1>
        <p><?=$ticket->user->name?> <?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?> - <?=$ticket->product->title?></p>

        <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
        <a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'close','id'=>$ticket->id_ticket))?>">
        <?=__('Close Ticket')?></a>
        <?endif?> 

        <?if(Auth::instance()->get_user()->id_role==Model_Role::ROLE_ADMIN):?>

            <form class="form-inline pull-right" method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>"> 
                <?= FORM::select('agent', $users, $ticket->id_user_support, array( 
                    'id' => 'agent', 
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'class'=>'form-control',
                    ))?> 
                <button type="submit" class="btn btn-info"><?=__('Assign')?></button>
            </form>


            <a href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'update','id'=>$ticket->order->pk())) ?>">
                <?=round($ticket->order->amount,2)?><?=$ticket->order->currency?> <?=Date::format($ticket->order->pay_date,'d-m-y')?>
            </a>

            <br>
            <a href="<?=Route::url('oc-panel', array('controller'=> 'user', 'action'=>'update','id'=>$ticket->id_user)) ?>">
                <?=$ticket->user->email?>
            </a>
            - <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index','id'=>'admin'))?>?search=<?=$ticket->user->email?>">
                <?=__('Tickets')?></a>

            <?if ($ticket->order->licenses->count_all()>0):?>
            <?foreach ($ticket->order->licenses->find_all() as $license):?>
                <br>
                <a href="http://<?=$license->domain?>" target="_blank">
                    <?=$license->domain?>
                </a>
                -
                <a href="<?=Route::url('oc-panel', array('controller'=> 'license', 'action'=>'update','id'=>$license->id_license)) ?>">
                    <?=(empty($license->domain))?__('Inactive license'):__('Active license')?>
                </a>
            <?endforeach?>
            <?endif?>
        <?endif?> 
	</div>

    <div class="row">
        <div class="col-md-2">
            <img class="ticket_image" src="<?=$ticket->user->get_profile_image()?>" width="120px" height="120px">
            <p>
                <?=$ticket->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($ticket->created))?><br>
                <?=$ticket->created?>
            </p>
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10">
            <p><?=Text::bb2html($ticket->description,TRUE)?></p>
        </div>
    </div>
    <hr>
    <?foreach ($replies as $reply):?>
    <div class="row <?=($ticket->id_user!==$reply->id_user)?'well':''?>" >
        <div class="col-md-2">
            <img class="ticket_image" src="<?=$reply->user->get_profile_image()?>" width="120px" height="120px">
            <p>
                <?=$reply->user->name?><br>
                <?=Date::fuzzy_span(Date::mysql2unix($reply->created))?><br>
                <?=$reply->created?>
            </p>
        </div>
        <div class="col-md-9">
            <p><?=Text::bb2html($reply->description,TRUE)?></p>
        </div>
    </div>
    <hr>
    <?endforeach?>

    <?if($ticket->status!=Model_Ticket::STATUS_CLOSED):?>
	<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>$ticket->id_ticket))?>">         
      <?php if ($errors): ?>
        <p class="message"><?=__('Some errors were encountered, please check the details you entered.')?></p>
        <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
        </ul>
        <?php endif ?>       


      <div class="form-group">
        <label class="col-md-2"><?=__("Reply")?>:</label>
        <div class="col-md-9 col-sm-9 col-xs-12">
        <textarea name="description" rows="10" class="form-control" required><?=core::post('description',__('Description'))?></textarea>
        </div>
      </div>

      <?=Form::token('reply_ticket')?>
      <div class="form-actions">
      	<a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'index'))?>" class="btn btn-default"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Reply')?></button>
      </div>
	</form>  
    <?endif?>  