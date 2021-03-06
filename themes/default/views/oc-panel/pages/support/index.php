<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="form-inline" method="get" action="<?=URL::current();?>">
    <div class="form-group pull-right">
        <div class="">
            <input type="text" class="form-control search-query" name="search" placeholder="<?=__('search')?>" value="<?=core::get('search')?>">
        </div>
    </div>
</form>

<div class="page-header">
	<h1><?=$title?></h1>

    <div class="btn-group">
        <a href="?status=-1" class="btn <?=(core::get('status',-1)==-1)?'btn-primary':'btn-default'?>">
            <?=__('All')?>
        </a>
        <?foreach (Model_Ticket::$statuses as $k => $v):?>
        <a href="?status=<?=$k?>" class="btn <?=(core::get('status',-1)==$k)?'btn-primary':'btn-default'?>">
            <?=$v?>
        </a>
        <?endforeach?>
    </div>

    <a class="btn btn-info pull-right" href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'new'))?>">
        <?=__('New')?></a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
             <tr>
                <th><?=__('Title')?></th>
                <th><?=__('Date')?></th>
                <th><?=__('Last Answer')?></th>
                <th><?=__('Agent')?></th>
                <th ></th>
            </tr>
        </thead>

        <tbody>
            <?foreach ($tickets as $ticket):?>
            <tr class="<?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'danger':''?>
                <?=($ticket->status==Model_Ticket::STATUS_HOLD)?'warning':''?>
                <?=($ticket->status==Model_Ticket::STATUS_READ)?'success':''?>">
                <td><span class="ww"><?=($ticket->title!='')?$ticket->title:Text::limit_chars(Text::removebbcode($ticket->description), 45, NULL, TRUE);?></span></td>
                <td><?=$ticket->created?></td>
                <td><?=(empty($ticket->read_date))?__('None'):$ticket->read_date?></td>
                <td><?=(!$ticket->agent->loaded())?__('None'):$ticket->agent->name?></td>
                <td><span class="label <?=($ticket->status==Model_Ticket::STATUS_CLOSED)?'label-danger':''?>
                                        <?=($ticket->status==Model_Ticket::STATUS_CREATED)?'label-info':''?>
                                        <?=($ticket->status==Model_Ticket::STATUS_READ)?'label-success':''?>
                                        <?=($ticket->status==Model_Ticket::STATUS_HOLD)?'label-warning':''?>">
                    <?=(Model_Ticket::$statuses[$ticket->status])?></span>
                </td>
                <td>
                    <a href="<?=Route::url('oc-panel',array('controller'=>'support','action'=>'ticket','id'=>($ticket->id_ticket_parent!=NULL)?$ticket->id_ticket_parent:$ticket->id_ticket))?>" class="btn btn-success">
                        <i class="glyphicon glyphicon-envelope"></i></a>
                </td>
            </tr>
            <?endforeach?>
        </tbody>

    </table>
</div>

<?=$pagination?>