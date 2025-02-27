<{if $module.name}>
    <li>
        <a href="<{$xoops_url}>/modules/<{$module.directory}>/"><{$module.name}></a>
        <{if $module.parent|default:''}>
            <ul>
                <{foreach item=parent from=$module.parent|default:null}>
                    <li>
                        <a href="<{$xoops_url}>/modules/<{$module.directory}>/<{$parent.url}>"><{$parent.title}></a>
                        <{if isset($show_subcategories)}>
                            <{if $parent.child|default:null}>
                                <ul>
                                    <{foreach item=child from=$parent.child|default:null}>
                                        <li><a href="<{$xoops_url}>/modules/<{$module.directory}>/<{$child.url}>"><{$child.title}></a></li>
                                    <{/foreach}>
                                </ul>
                            <{/if}>
                        <{/if}>
                    </li>
                <{/foreach}>
            </ul>
        <{/if}>
        <{if isset($show_sublink)}>
            <{if $module.sublinks}>
                <ul class="sublink">
                    <{foreach item=sublink from=$module.sublinks|default:null}>
                        <li><a href="<{$sublink.url}>"><{$sublink.name}></a></li>
                    <{/foreach}>
                </ul>
            <{/if}>
        <{/if}>
    </li>
<{/if}>
