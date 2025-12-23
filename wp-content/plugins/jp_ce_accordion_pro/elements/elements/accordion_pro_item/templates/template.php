<?php



// Image
$image = $this->render("{$__dir}/template-image");

// Image align
$grid = $this->el('div', [

    'class' => [
        'uk-child-width-expand',
        $element['image_grid_column_gap'] == $element['image_grid_row_gap'] ? 'uk-grid-{image_grid_column_gap}' : '[uk-grid-column-{image_grid_column_gap}] [uk-grid-row-{image_grid_row_gap}]',
        'uk-flex-middle {@image_vertical_align}',
    ],

    'uk-grid' => true,
]);

$cell_image = $this->el('div', [

    'class' => [
        'uk-width-{image_grid_width}@{image_grid_breakpoint}',
        'uk-flex-last@{image_grid_breakpoint} {@image_align: right}',
    ],

]);

$cell_content = $this->el('div', [

    'class' => [
        'uk-margin-remove-first-child',
    ],

]);
if($props['content_type'] == 0)
{
?>

<?php if ($image && in_array($element['image_align'], ['left', 'right'])) : ?>

    <?= $grid($element) ?>
        <?= $cell_image($element, $image) ?>
        <?= $cell_content($element) ?>
            <?= $this->render("{$__dir}/template-content") ?>
            <?= $this->render("{$__dir}/template-link") ?>
        <?= $cell_content->end() ?>
    </div>

<?php else : ?>

    <?= $element['image_align'] == 'top' ? $image : '' ?>
    <?= $this->render("{$__dir}/template-content") ?>
    <?= $this->render("{$__dir}/template-link") ?>
    <?= $element['image_align'] == 'bottom' ? $image : '' ?>

<?php endif ?>
<?php
}
/*if($props['content_type'] == 1)
{
	$rand = uniqid();
	echo '<div id="jp_accordation_model_'.$rand.'" class="">';
		if(empty($widget->id))
		{
		echo $props['content'];
		}
	echo '</div>';
	
	if(!empty($widget->id))
	{
	?>
    <script>
	 document.addEventListener('DOMContentLoaded', function () {
		 document.querySelector("#jp_accordation_model_<?= $rand ?>").innerHTML = document.querySelector("#<?= $widget->id ?>").innerHTML;
		 document.querySelector("#<?= $widget->id ?>").remove();
	});
	</script>
    <?php
	}
}*/
if ($props['content_type'] == 1) {
    $rand = uniqid();
    echo '<div id="jp_accordation_model_' . $rand . '">';
    echo !empty($widget->content) ? $widget->content : $props['content'];
    echo '</div>';
}
else if($props['content_type'] == 2 && !empty($props['section_id']))
{
	$section_id = $props['section_id'];
	$rand = uniqid();
	echo '<div id="jp_accordation_section_'.$rand.'" class="jp_section_content">';
	echo '</div>';
	?>
    <style>
		.jp_section_content .uk-container:first-child
		{
			padding-left:0px !important;
			padding-right:0px !important;
		}
		</style>
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			if(document.getElementById("<?= $section_id ?>") !== null)
			{
				element = document.querySelector("#<?= $section_id ?> .uk-container");
                elementParent = element.parentElement;
				destElement = document.querySelector("#jp_accordation_section_<?= $rand ?>");
				elementParent.removeChild(element);
				destElement.appendChild(element); 
                document.querySelector("#<?= $section_id ?>").remove(); 
			}
			else
			{
				document.querySelector("#<?php echo $element['current_id']; ?>").remove();
				//document.querySelector("#jp_accordation_section_<?= $rand ?>").innerHTML = "<div class='uk-alert uk-alert-warning'>Given Section id not found. Kindly recheck ID.</div>";
			}
		});
		</script>
    <?php
}
else if($props['content_type'] == 3)
{
	$rand = uniqid();
	echo '<div id="jp_switcher_section_'.$rand.'" class="jp_section_content">';
	$content = $builder->render($children);
	echo $content;
	echo '</div>';
}
