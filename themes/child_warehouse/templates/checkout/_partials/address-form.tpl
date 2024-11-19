{extends file='parent:checkout/_partials/address-form.tpl'}

{block name='form_buttons'}
  {if !$form_has_continue_button}
    <button type="submit" class="btn btn-primary pull-xs-right">{l s='Save' d='Shop.Theme.Actions'}</button>
    <a class="js-cancel-address cancel-address pull-xs-right" href="?cancelAddress={$type}&controller=order">{l s='Cancel' d='Shop.Theme.Actions'}</a>
	<!--&controller=order added by skt in href-->
  {else}
    <form>
      <button type="submit" class="continue btn btn-primary btn-block btn-lg" name="confirm-addresses" value="1">
          {l s='Continue' d='Shop.Theme.Actions'}
      </button>
      {if $customer.addresses|count > 0}
        <a class="js-cancel-address cancel-address pull-xs-right" href="?cancelAddress={$type}&controller=order">{l s='Cancel' d='Shop.Theme.Actions'}</a>
		<!--&controller=order added by skt in href-->
      {/if}
    </form>
  {/if}
{/block}