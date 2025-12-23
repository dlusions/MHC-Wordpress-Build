<?php

global $wp_session;

$activetab = 0;
if(!empty($_REQUEST['activetab']))
{
	$activetab = $_REQUEST['activetab'];
}


$randid = uniqid();
if($props['remember_tab'] && empty($props['uniqueid']))
{
	echo '<div class="uk-alert uk-alert-warning">Please Add Unique ID in params to enable "Remember Last Used Tab"</div>';
}
if($props['remember_tab'] && !empty($props['uniqueid']))
{
	$props['connect'] = $props['uniqueid'];
}
else
{
	$props['connect'] = "js-{$this->uid()}";
	$props['uniqueid'] = $props['connect'];
}

$activetab_session =  $_SESSION[$props['uniqueid']] ?? '';

$url_parts = parse_url( home_url() );
$currenturl = $url_parts['scheme'] . "://" . $url_parts['host'] . add_query_arg( NULL, NULL );


if($activetab_session != '' && $props['remember_tab'])
{
	$activetab = str_replace($props['uniqueid']."_", "", $activetab_session);
	?>
    <script>
	  document.addEventListener('DOMContentLoaded', function () {
		  
		 element = document.querySelector("#<?php echo $props['uniqueid']; ?>");
		 UIkit.accordion(element).toggle(<?php echo $activetab; ?>, true);
		 
	   });
	</script>
    <?php
}
	
$custom_style = $props['custom_style'];
$heading_icon = $props['heading_icon'];
$heading_active_icon = $props['heading_active_icon'];
$heading_icon_bg = $props['heading_icon_bg'];
$heading_icon_align = $props['heading_icon_align'];
$heading_icon_width = $props['heading_icon_width'];
$heading_padding = $props['heading_padding'];
$heading_border = $props['heading_border'];
$heading_bg = $props['heading_bg'];
$heading_color = $props['heading_color'];
$content_border = $props['content_border'];
$content_bg = $props['content_bg'];
$content_color = $props['content_color'];
$content_margin_top = $props['content_margin_top'];
$content_padding = $props['content_padding'];

$heading_border_width = $props['heading_border_width'];
$heading_icon_padding = $props['heading_icon_padding'];
$content_border_width = $props['content_border_width'];
$heading_icon_transition = $props['heading_icon_transition'];

$el = $this->el('div', [

    'uk-accordion' => [
        'multiple: {multiple};',
        'collapsible: {0};' => $props['collapsible'] ? 'true' : 'false',
    ],

]);
?>
<div id="<?php echo $attrs['id']; ?>">
<?php
$randomclass = "custom_acc_".uniqid();
$attrs['class'][]  = $randomclass;
$attrs['class'][]  = $props['class'];
$attrs['id'] = $props['connect'];
?>

<?= $el($props, $attrs) ?>

    <?php foreach ($children as $child) :

        $content = $this->el('div', [

            'class' => [
                'uk-accordion-content',
                'uk-margin-remove-first-child' => !$child->props['image'] || !in_array($props['image_align'], ['left', 'right']),
            ],

        ]);
		$hrefvalue = "#";
		if($props["enable_url"])
		{
			$hrefvalue = $currenturl."#".$child->props["unique_id"];
		}
	    $rand = $props['connect']."_".$i;
		$props["current_id"] = $rand;
		$i++;
		
		$accordiontitle_class = "el-accordion-title";
		if(!empty($props['title_style'])){ $accordiontitle_class.=" uk-".$props['title_style']; }
		if(!empty($props['title_color'])){ $accordiontitle_class.=" uk-text-".$props['title_color']; }
		if(!empty($props['title_font_family'])){ $accordiontitle_class.=" uk-font-".$props['title_font_family']; }
    ?>
     <div id="<?php echo $rand; ?>" class="el-item jp_<?php echo $child->props["unique_id"]; ?>">

               <a class="uk-accordion-title" href="<?php echo $hrefvalue; ?>">
			 <?php  echo "<".$props['title_element'] ?> class="<?php echo $accordiontitle_class; ?> uk-margin-remove-bottom" ><?= $child->props['title'] ?>  <?= "</".$props['title_element'] ?>>
               </a>



        <?= $content($props) ?>
            <?= $builder->render($child, ['element' => $props]) ?>
        <?= $content->end() ?>

    </div>

    <?php endforeach ?>

</div>
</div>
<?php
if($custom_style == 'custom')
{
    ?>
    <style>
    .<?php echo $randomclass; ?> .uk-accordion-title *
    {
        color:<?php echo $heading_color; ?> !important;
    }
    .<?php echo $randomclass; ?> .uk-accordion-title
    {
        padding:<?php echo $heading_padding; ?>px !important;
        color:<?php echo $heading_color; ?> !important;
        background:<?php echo $heading_bg; ?> !important;
        border:<?php echo $heading_border_width; ?>px solid <?php echo $heading_border; ?>  !important;
        position:relative;
        <?php if($heading_icon_align == 'left') { ?>
        padding-left:<?php echo $heading_icon_width+($heading_icon_padding * 2)+$heading_padding; ?>px !important;
        <?php } else { ?>
        padding-right:<?php echo $heading_icon_width+($heading_icon_padding * 2)+$heading_padding; ?>px !important;
        <?php } ?>
    }
    .<?php echo $randomclass; ?>  .uk-accordion-title::before
    {
        <?php if($heading_icon_transition == "scaleup" ) { ?>
        transform: scale(1);
        transition: .3s ease-in-out;
        <?php } ?>
        <?php if($heading_icon_transition == "scaledown" ) { ?>
        transform: scale(1.3);
        transition: .3s ease-in-out;
        <?php } ?>
        <?php if($heading_icon_transition == "spin" ) { ?>
        transform: rotate(0) scale(1);
        transition: .3s ease-in-out;
        <?php } ?>
         <?php if($heading_icon_transition == "slide" ) { ?>
        margin-left:10px !important;
        transform: scale(1);
        transition: .3s ease-in-out;
        <?php } ?>
        <?php if($heading_icon_transition == "fade" ) { ?>
        opacity: 1;
        transition: .3s ease-in-out;
        <?php } ?>
        display: block;
        position: absolute;
        top: 0;
        bottom: 0;
        <?php if(!empty($heading_icon)) { ?>
        background-image: url('<?php echo get_site_url()."/".$heading_icon; ?>') !important;
        <?php } ?>
        background-color: <?php echo $heading_icon_bg; ?> !important;
        background-size:<?php echo $heading_icon_width; ?>px !important;
        margin-left:0px;
        background-repeat: repeat;
        width: <?php echo $heading_icon_width+($heading_icon_padding * 2); ?>px;
        height: 100%;
        background-repeat: no-repeat;
        background-position: center center;
        <?php if($heading_icon_align == 'left') { ?>
        left: 0;
        margin-right:15px;
        margin-left:0px;
        <?php } else { ?>
        right:0; 
        margin-right:0px;
        margin-left:15px;
        <?php } ?>
    }
    .<?php echo $randomclass; ?>   .uk-open > .uk-accordion-title::before 
    {
        <?php if(!empty($heading_active_icon)) { ?>
            background-image:  url('<?php echo get_site_url()."/".$heading_active_icon; ?>') !important;
        <?php }elseif(!empty($heading_icon)) {?>
                background-image:  url('<?php echo get_site_url()."/".$heading_icon; ?>') !important;
        <?php }?>


            <?php if($heading_icon_transition == "scaleup" ) { ?>  
                transform: scale(1.3);
            <?php }?>
            <?php if($heading_icon_transition == "scaledown" ) { ?>  
                transform: scale(1);
            <?php }?>
            <?php if($heading_icon_transition == "spin" ) { ?>  
                transform: rotate(180deg) scale(1);
            <?php }?>
            <?php if($heading_icon_transition == "slide" ) { ?>  
                margin-left: 0px !important;
            <?php }?>
            <?php if($heading_icon_transition == "fade" ) { ?>  
                opacity: 0.5;
            <?php }?>
                 
    }
    .<?php echo $randomclass; ?>  .uk-accordion-content
    {
        border:<?php echo $content_border_width; ?>px solid <?php echo $content_border; ?> !important;
        background:<?php echo $content_bg; ?> !important;
        <?php if (empty($content_margin_top)){ ?>
        border-top:none !important;
        <?php } ?>
        padding:<?php echo $content_padding; ?>px !important;
        margin-top:<?php echo $content_margin_top;  ?>px !important;
        
    }
    .<?php echo $randomclass; ?>  .uk-accordion-content *
    {
        color:<?php echo $content_color; ?>;
    }
     .<?php echo $randomclass; ?>  > :nth-child(n+2) 
     {
         border: unset !important;
box-shadow: unset !important;
     }
    </style>
<?php
}
?>
<script>
function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

<?php
if($activetab == 0)
{
?>
hashvalue = document.location.hash;
if(hashvalue != "")
{ 

 hashvalue = hashvalue.replace("#", "");
 if(document.querySelector(".jp_"+hashvalue) != null)
 {
	 	
	 uid = "<?php echo $props['uniqueid']; ?>";
	 
	 activeid = document.querySelector(".jp_"+hashvalue).id;
	 activeid = activeid.replace(uid+"_", "");
	 if(isNumeric(activeid))
	 {
		 element = document.querySelector("#<?php echo $props['uniqueid']; ?>");
		 UIkit.accordion(element).toggle(activeid, true);
	 }
 }
 
}

<?php
}
?>
document.addEventListener('DOMContentLoaded', function () {	 
		<?php
		
		if($props['scrollto'])
		{
			?>
			setTimeout(function(){
			  window.scroll({ 
				  top: document.querySelector("#<?php echo $props['connect']; ?>").getBoundingClientRect().top  <?php echo $props['scroll_offset']; ?>,
				  left: 0, 
				  behavior: "<?php echo $props['scroll_duration']; ?>"
				})
			}, 500);
			<?php
		} ?>
});

</script>
<?php
if($props['remember_tab'] && !empty($props['uniqueid']))
{
?>
<script>
document.addEventListener('DOMContentLoaded', function () {

     UIkit.util.on('#<?php echo $props['connect']; ?>', 'shown', function (ev) {
            activeid = "";

		      const elements = document.querySelectorAll("#<?php echo $props['uniqueid']; ?> .el-item");
			  elements.forEach(function (element) {
				classname = element.class;
				
				if(element.classList.contains("uk-open"))
				{
					activeid = element.id;
					hrlink_val = document.querySelector("#"+activeid +" a").href;
				
					<?php if($props["enable_url"]) { ?>
				    window.history.pushState(null, "", hrlink_val);
					<?php } ?>
				}
			  });
			
			if(activeid != '')
			{
				   var xhttp = new XMLHttpRequest();
				   xhttp.onreadystatechange = function() {
					  if (this.readyState == 4 && this.status == 200) {
					   }
					 };
			  	   xhttp.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>?action=jp_accordion_save&id=<?php echo $props['uniqueid']; ?>&active="+activeid, true);
				   xhttp.send();
			}
	});
});
</script>
<?php
}
else if($props["enable_url"])
{
   ?>
   <script>
    document.addEventListener('DOMContentLoaded', function () {
		
		  UIkit.util.on('#<?php echo $props['connect']; ?>', 'shown', function (ev) {
            activeid = "";
			
			 const elements = document.querySelectorAll("#<?php echo $props['uniqueid']; ?> .el-item");
		    elements.forEach(function (element) {
				
				classname = element.class;
				if(element.classList.contains("uk-open"))
				{
					activeid = element.id;
					hrlink_val = document.querySelector("#"+activeid +" a").href;
					if(hrlink_val != '#')
					{
					     window.history.pushState(null, "", hrlink_val);
					}
				}
			});
			
		});
    });
   </script>
   
   <?php
}
?>