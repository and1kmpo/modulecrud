<div class="container">
    <div class="panel">
        <div class="panel-heading">
            {l s='Textos personalizados' mod='modulecrud'}
        </div>
        <div class="panel-body">
            
        {if isset($textos) && $textos|count}
            <table class='table>
            <thead>
                <th>{l s='ID' mod='modulecrud'}</th>
                <th>{l s='Texto' mod='modulecrud'}</th>
            </thead>
        
            <tbody>
                {* {foreach $textos as $t}
                   <tr>
                        <td>{$t.id|intval}</td>
                        <td>{$t.texto|escape:'html':'UTF-8'}</td>
                   </tr>
                {/foreach} *}
            </tbody>

       </table>
        {else}
            <p class="alert alert-info">{l s='Aún no has creado ningún texto' mod='modulecrud'}</p>
        {/if}

        </div>

        <div class="panel-footer">
            <p class="text-right">
                <a href="{$urls.add|escape:'html':'UTF-8'}" class="btn btn-default">{l s='Añadir texto' mod='modulecrud'}</a>
            </p>
        </div>
    </div>
</div>