<?php
/*
  Plugin Name: ClickToCall
  Plugin URI: https://github.com/mikejakobsen/wp-popup
  Description:
  Author: Hoeks
  Version: 1.0.1
  Author URI: http://mikejakobsen.com
 */

// Call the functions
add_action('admin_menu', 'ClickToCall_create_options_page');
add_action('admin_init', 'ClickToCall_lets_get_this_party_started');
add_action('wp_footer', 'ClickToCall_add_plugin', 100);
add_action('wp_print_styles', 'ClickToCall_add_css');
add_action('wp_head', 'ClickToCall_add_mobile_size');


//Settings Menu
// #Todo: 6 properties, wtf??
function ClickToCall_create_options_page()
{
  add_options_page('Click To Call indstillinger', 'Click To Call', 'publish_posts', 'indstillinger', 'ClickToCall_options_page', '');
}


// whitelist options
function ClickToCall_lets_get_this_party_started()
{
  register_setting('sitename_custom_options-group', 'ClickToCall_options');
  wp_register_style('jquery-ui.css', plugins_url('dist/jquery-ui.min.css', __FILE__));

  wp_register_style('font-awesome.css', plugins_url('dist/font-awesome.css', __FILE__));
  wp_register_style('bootstrap.min.css', plugins_url('dist/bootstrap.min.css', __FILE__));
  wp_register_script('bootstrap.js', plugins_url('dist/bootstrap.min.js', __FILE__));
  wp_register_style('spectrum.css', plugins_url('dist/spectrum.css', __FILE__));
  wp_register_script('spectrum.js', plugins_url('dist/spectrum.js', __FILE__));

  // Call Scripts
  wp_enqueue_style('font-awesome.css');
  wp_enqueue_style('bootstrap.min.css');
  wp_enqueue_script('bootstrap.js');
  wp_enqueue_style('spectrum.css');
  wp_enqueue_script('spectrum.js');

  // tell WordPress to load jQuery UI
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-slider');
  wp_enqueue_style('jquery-ui.css');
}


// Click to Call's settings page
function ClickToCall_options_page()
{

  // #Todo: Ability to choose animation and FA icon
  $ClickToCall_options = get_option('ClickToCall_options');

  $mobile_size_min = 0;
  $mobile_size_max = 2000;
  $bg_color_default = '#ffffff';
  $text_color_default = '#ffffff';
  $callbutton_color_default = '#0c3';
  $mapbutton_color_default ='#fc3';


  if (isset($_GET['page']) && $_GET['page'] == 'indstillinger') {
?>

    <script>
    jQuery(function() {

      jQuery( "#slider" ).slider({
      range: "max",
        min: <?php echo $mobile_size_min; ?>,
        max: <?php echo $mobile_size_max; ?>,
        value: jQuery('#mobilSize').val(),
                slide: function( event, ui ) {
                  jQuery( "#mobilSize" ).val( ui.value );
                  jQuery(ui.value).val(jQuery('#mobilSize').val());
  }
  });

  jQuery('#mobilSize').change(function(){
    jQuery('#slider').slider('value', jQuery('#mobilSize').val());

  });


  jQuery("#background_color_picker").spectrum({
  preferredFormat: "hex3",
    showPalette: true,
    showSelectionPalette: true,
    palette: [ ],
    maxSelectionSize: 12,
    color: "<?php if (!empty($ClickToCall_options['bg_color'])) {
    echo $ClickToCall_options['bg_color'];
  } else {
    echo $bg_color_default;
  } ?>",
showInput: true,
change: function(color) {
  jQuery('#background_color').val(color);
  }
  });


  jQuery("#callbutton_color_picker").spectrum({
  preferredFormat: "hex3",
    showPalette: true,
    showSelectionPalette: true,
    palette: [ ],
    maxSelectionSize: 12,
    color: "<?php if (!empty($ClickToCall_options['call_color'])) {
    echo $ClickToCall_options['call_color'];
  } else {
    echo $text_color_default;
  } ?>",
showInput: true,
change: function(color) {
  jQuery('#callbutton_color').val(color);
  }
  });


  jQuery("#text_color_picker").spectrum({
  preferredFormat: "hex3",
    showPalette: true,
    showSelectionPalette: true,
    palette: [ ],
    maxSelectionSize: 12,
    color: "<?php if (!empty($ClickToCall_options['text_color'])) {
    echo $ClickToCall_options['text_color'];
  } else {
    echo $callbutton_color_default;
  } ?>",
showInput: true,
change: function(color) {
  jQuery('#text_color').val(color);
  }
  });


  jQuery("#mapbutton_color_picker").spectrum({
  preferredFormat: "hex3",
    showPalette: true,
    showSelectionPalette: true,
    palette: [ ],
    maxSelectionSize: 12,
    color: "<?php if (!empty($ClickToCall_options['map_color'])) {
    echo $ClickToCall_options['map_color'];
  } else {
    echo $mapbutton_color_default;
  } ?>",
showInput: true,
change: function(color) {
  jQuery('#mapbutton_color').val(color);
  }
  });
  });

  /* #Todo: Fix indentation in the html part - editorconfig file? */
  /* #Todo: Use Phone number from the general settings */
  </script>
        <div class='container'>
          <div class="row">
            <div class="col-xs-12">
              <h1 style="text-shadow: 2px 2px 0 white, 3px 3px 0 #ddd; padding: 30px 0;">Click To Call</h1>
              <p>Ryddes adresse felterne, vises kun "Ring nu". Fjernes Tlf vises kun "Find vej".</p>
              <p>Breakpoint definer hvilken opløsning pluginet aktiveres ved.</p>
              <p>Repo: https://github.com/mikejakobsen/wp-popup</p>
              <p>Backlog: https://github.com/mikejakobsen/wp-popup/blob/master/Todo.md</p>
            </div>
            <form method="post" action="options.php" >
<?php
    wp_nonce_field('ClickToCall_options');
  settings_fields('sitename_custom_options-group');
?>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="ClickToCall_options" />

                    <div class="form-group">
                        <div class="form-group row">
                          <label class="col-xs-2 col-form-label" for="ClickToCall_options[phone]">Tlf:</label>
                          <div class="col-xs-10">
                          <input class="form-control" name="ClickToCall_options[phone]" value='<?php
  $ClickToCall_options['phone'] = str_replace(
    array('+',' ','(',')','.'),
    array('','','','-','-'),
    $ClickToCall_options['phone']
  );
  echo $ClickToCall_options['phone']; ?>' />
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[gade]">Gade:</label>
                      <div class="col-xs-10">
                        <input class="form-control" name="ClickToCall_options[gade]" value='<?php echo $ClickToCall_options['gade'] ?>' placeholder="Gade" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[land]">Land:</label>
                      <div class="col-xs-10">
                      <input class="form-control" name="ClickToCall_options[land]" value='<?php echo $ClickToCall_options['land'] ?>' placeholder="Land"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[post_nr]">Post nummer:</label>
                      <div class="col-xs-10">
                      <input class="form-control" name="ClickToCall_options[post_nr]" value='<?php echo $ClickToCall_options["post_nr"] ?>' placeholder="Post nr"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[mobile_size]">Breakpoint (px):</label>
                      <div class="col-xs-10">
                        <input class="form-control" type="number" id="mobilSize" name="ClickToCall_options[mobile_size]" value='<?php echo $ClickToCall_options['mobile_size']; ?>' min=<?php echo $mobile_size_min; ?> max=<?php echo $mobile_size_max; ?>/>
                        <div class="mx-auto" id="slider"></div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[ring_nu]">Ring nu knappen</label>
                      <div class="col-xs-10">
                        <input class="form-control" type="text" id="callbutton_text" name="ClickToCall_options[ring_nu]" value="<?php echo (empty($ClickToCall_options['ring_nu'])?'Ring Nu':$ClickToCall_options['ring_nu']); ?>" placeholder="Ring Nu" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[map_text]">Find vej knappen</label>
                      <div class="col-xs-10">
                        <input class="form-control" type="text" id="mapbutton_text" name="ClickToCall_options[map_text]" value="<?php echo (empty($ClickToCall_options['map_text'])?'Find vej':$ClickToCall_options['map_text']); ?>" placeholder="Find Vej"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label">Ring nu farve</label>
                      <div class="col-xs-10">
                        <input class="form-control" type='text' id="callbutton_color_picker"  />
                        <input hidden="true" type="text" id="callbutton_color" name="ClickToCall_options[call_color]" value="<?php echo $ClickToCall_options['call_color']; ?>" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label">Find vej farve</label>
                      <div class="col-xs-10">
                        <input class="form-control" type='text' id="mapbutton_color_picker"  />
                        <input hidden="true" type="text" id="mapbutton_color" name="ClickToCall_options[map_color]" value="<?php echo $ClickToCall_options['map_color']; ?>"/>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label">Baggrunds farve</label>
                      <div class="col-xs-10">
                        <input class="form-control" type='text' id="background_color_picker"  />
                        <input hidden="true" type="text" id="background_color" value='<?php echo $ClickToCall_options['bg_color']; ?>' name="ClickToCall_options[bg_color]" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label">Tekst farve</label>
                      <div class="col-xs-10">
                        <input class="form-control" type='text' id="text_color_picker"  />
                        <input hidden="true" type="text" id="text_color" value='<?php echo $ClickToCall_options['text_color']; ?>' name="ClickToCall_options[text_color]" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[fadeIn]">Animations varighed:</label>
                      <div class="col-xs-10">
                        <input class="form-control" type="text" id="callbutton_time" name="ClickToCall_options[fadeIn]" value="<?php echo (empty($ClickToCall_options['fadeIn'])?'Tid i sekunder':$ClickToCall_options['fadeIn']); ?>" placeholder="Tid i sekunder" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-xs-2 col-form-label" for="ClickToCall_options[animation]">Vælg animation</label>
                      <div class="col-xs-10">
                        <input class="form-control" type="text" id="callbutton_animation" name="ClickToCall_options[animation]" value="<?php echo (empty($ClickToCall_options['animation'])?'Animation':$ClickToCall_options['animation']); ?>" placeholder="Animation" />
                        <p>https://daneden.github.io/animate.css/</p>
                      </div>
                    </div>
                    <input type="submit" class="btn btn-secondary" value="Gem" />
                  </div>
              </form>
        </div>
    </div>
<?php
  }
}


// adding custom footer to the mobile version
function ClickToCall_add_plugin()
{

  $ClickToCall_options = get_option('ClickToCall_options');
  $tel = $ClickToCall_options['phone'];
  $map_link = ClickToCall_map_link_constructor();
  $ring_nu = $ClickToCall_options['ring_nu'];
  $map_text = $ClickToCall_options['map_text'];

  // counting number of buttons to display
  $count = 0;
  if (!empty($tel)) {
    $count++;
  }
  if (!empty($map_link)) {
    $count++;
  }

  // The real action
  if ($count != 0) {
    echo "<div id='knapper' class='btn__$count'>";
    if (!empty($tel)) {
      echo "<div><a href='tel:$tel' id='ring_nu' onClick= \" ga('send', 'event', 'Phone Call', 'Click to Call', '$tel'); \" ><i class='fa fa-phone'></i><span class='text'> $ring_nu</span></a></div>";
    }
    if (!empty($map_link)) {
      echo "<div><a href='$map_link' id='find_vej' target='_Blank'><i class='fa fa-globe'></i><span class='text'> $map_text</span></a></div>";
    }
    echo "</div>";
  }
}


// Load Css til knapperne
function ClickToCall_add_css()
{

  wp_register_style('amIPrettyMom', plugins_url('dist/style.css', __FILE__));
  wp_enqueue_style('amIPrettyMom');
}


function ClickToCall_add_mobile_size()
{

  $ClickToCall_options = get_option('ClickToCall_options');
  $mobile_size = $ClickToCall_options['mobile_size'];
  $background_color = $ClickToCall_options['bg_color'];
  $text_color = $ClickToCall_options['text_color'];
  $fadeIn_time = $ClickToCall_options['fadeIn'];
  $animation = $ClickToCall_options['animation'];
  $callbutton_color = $ClickToCall_options['call_color'];
  $mapbutton_color = $ClickToCall_options['map_color'];

  // Display: block if max-width is above the set value
  $output="<style>
    @media screen and (max-width: {$mobile_size}px) { div#knapper, div#knapper_div { display:block; } }

    div#knapper {
    background: {$background_color};
    animation: {$animation} {$fadeIn_time}s;
}

div#knapper div a#ring_nu {
  background: {$callbutton_color};
  color: {$text_color};
}

div#knapper div a#find_vej {
  background: {$mapbutton_color};
  color: {$text_color};
}

</style>";

echo $output;
}

function ClickToCall_map_link_constructor()
{

  $map_link = "https://maps.google.com/?q=";
  $address = "";
  $suffix = ", ";
  $ClickToCall_options = get_option('ClickToCall_options');

  if (!empty($ClickToCall_options['gade'])) {
    $address .= $ClickToCall_options['gade']. $suffix;
  }
  if (!empty($ClickToCall_options['by'])) {
    $address .= $ClickToCall_options['by']. $suffix;
  }
  if (!empty($ClickToCall_options['land'])) {
    $address .= $ClickToCall_options['land']. $suffix;
  }
  if (!empty($ClickToCall_options["post_nr"])) {
    $address .= $ClickToCall_options["post_nr"];
  }


  if (!empty($address)) {
    $map_link .= urlencode($address);
    return $map_link;
  } else {
    return null;
  }
}
