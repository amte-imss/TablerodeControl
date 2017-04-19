<?php
/**
 * @author Christian Garcia
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('render_menu'))
{

    function render_menu($menu = [], $dropdown = false)
    {
        $html = '';
        ob_start();
        ?>
        <ul class="<?php echo ($dropdown ? 'dropdown-menu' : ''); ?>" >
            <?php
            foreach ($menu as $item)
            {
                ?>
                <li class="<?php echo (isset($item['childs']) ? 'dropdown' : '') ?>" style="list-style-type: none;">

                    <a href="<?php echo (isset($item['link']) ? site_url().$item['link'] : '#'); ?>" class="<?php echo (isset($item['childs']) ?: 'dropdown-toggle'); ?>" <?php echo (isset($item['childs']) ? 'data-toggle="dropdown"' : ''); ?>>
                        <i class="material-icons">dashboard</i>
                        <?php
                        if (isset($item['titulo']))
                        {
                            ?>
                            <?php echo $item['titulo']; ?>
                            <?php
                        }
                        ?>
                    </a>
                    <?php
                    if (isset($item['childs']))
                    {
                        //pr($item['childs']);
                        echo render_menu($item['childs'], true);
                    }
                    ?>
                </li>
                <?php } ?>
            </ul>
            <?php
            $html = ob_get_contents();
            ob_get_clean();
            return $html;
        }
    }
    