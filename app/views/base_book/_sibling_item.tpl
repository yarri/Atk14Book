<li{if $sibling->getId()==$chapter->getId()} class="active"{/if}>{a action=detail id=$sibling}{$sibling->getTitle()|h}{/a}</li>
