<?php
/**
 * @author Christian Garcia
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('render_menu'))
{

    function render_menu($menu = [])
    {
        $html = '';
        ob_start();
        ?>
        <ul class="nav">
            <?php
            foreach ($menu as $item)
            {
                ?>
                <li>
                    <?php
                    if (isset($item['link']))
                    {
                        ?>
                        <a href="<?php echo $item['link']; ?>">
                        <?php } ?>
                        <i class="material-icons">dashboard</i>
                        <?php
                        if (isset($item['titulo']))
                        {
                            ?>
                            <p><?php echo $item['titulo']; ?></p>
                            <?php
                        }
                        if (isset($item['link']))
                        {
                            ?>
                        </a>
                        <?php
                    }
                    if (isset($item['childs']))
                    {
                        //pr($item['childs']);
                        echo render_menu($item['childs']);
                    }
                    ?>

                </li>
                <?php
            }
            ?>
        </ul>
        <?php
        $html = ob_get_contents();
        ob_get_clean();
        return $html;
    }

}