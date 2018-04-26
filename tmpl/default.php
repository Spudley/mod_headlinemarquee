<?php
/**
 * @package     mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="<?php echo $marquee->getClassName();?>" id="<?php echo $marquee->getDivID();?>" style="overflow:hidden;">
    <?php echo $marquee->getMarqueeText();?>
</div>
<script>
jQuery("#<?php echo $marquee->getDivID();?>").marquee(<?php echo json_encode($marquee->getScriptJSON());?>);
</script>