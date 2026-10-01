<?php
/**
 * Template Name: Size & Fit Guide
 *
 * @package Loomaama
 */

get_header();
?>

<main>
    <!-- ANNOUNCEMENT BAR -->
    

    <!-- HERO SECTION -->
    <header class="size-hero">
        <div class="size-hero-img">
            <?php $hero_image = get_field('size_hero_image'); ?>
            <?php if ($hero_image): ?>
                <img src="<?php echo esc_url($hero_image); ?>" alt="Size guide hero">
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1800&q=80" alt="Size guide hero">
            <?php endif; ?>
        </div>
        <div class="size-hero-copy">
            <div class="size-announcement mb-5">
        <?php echo esc_html(get_field('size_announcement_text') ?: 'LOOMAAMA Size & Fit Guide'); ?>
    </div>
            <div class="eyebrow"><?php echo esc_html(get_field('size_hero_eyebrow') ?: 'Find Your Size'); ?></div>
            <h1><?php echo wp_kses_post(get_field('size_hero_title') ?: 'Find Your Natural Fit'); ?></h1>
            <p><?php echo wp_kses_post(get_field('size_hero_text') ?: 'Choose the size that lets your LOOMAAMA piece sit comfortably, move easily, and fall naturally.'); ?></p>
        </div>
    </header>

    <!-- CATEGORY GRID -->
    <section class="size-category-grid">
        <?php 
        $categories = get_field('size_categories');
        if ($categories):
            foreach ($categories as $cat):
        ?>
        <a class="size-category" href="<?php echo esc_url($cat['category_link']); ?>" style="background-image: url('<?php echo esc_url($cat['category_image']); ?>');">
            <span><?php echo esc_html($cat['category_name']); ?></span>
        </a>
        <?php 
            endforeach;
        else:
            // Default categories
            $default_cats = [
                ['name' => 'Tops', 'link' => '#tops', 'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=80'],
                ['name' => 'Pants', 'link' => '#pants', 'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=900&q=80'],
                ['name' => 'Dresses & Skirts', 'link' => '#dresses', 'image' => 'https://images.unsplash.com/photo-1506629905607-d9e297d2c88d?auto=format&fit=crop&w=900&q=80'],
                ['name' => 'Outer Layers', 'link' => '#outer', 'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=80'],
                ['name' => 'Accessories', 'link' => '#accessories', 'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=900&q=80'],
            ];
            foreach ($default_cats as $cat):
        ?>
        <a class="size-category" href="<?php echo esc_url($cat['link']); ?>" style="background-image: url('<?php echo esc_url($cat['image']); ?>');">
            <span><?php echo esc_html($cat['name']); ?></span>
        </a>
        <?php 
            endforeach;
        endif; 
        ?>
    </section>

    <!-- BEFORE YOU CHOOSE SECTION -->
    <section class="size-section">
        <div class="size-center">
            <div class="eyebrow"><?php echo esc_html(get_field('size_before_eyebrow') ?: 'Before You Choose'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_before_title') ?: 'LOOMAAMA garments are made from Himalayan nettle cloth.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('size_before_text') ?: 'The fabric has a natural texture, structure, and soft drape. Choose your usual size for a balanced fit. Size up if you prefer more room and a relaxed look. Choose a closer fit only for pieces made to sit near the body.'); ?></p>
        </div>
    </section>

    <!-- SIZE CHARTS SECTION -->
    <section class="size-section alt" id="size-charts">
        <div class="size-center">
            <div class="eyebrow"><?php echo esc_html(get_field('size_charts_eyebrow') ?: 'Size Charts'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_charts_title') ?: 'Use the size chart as a starting point.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('size_charts_subtitle') ?: 'For the best fit, also check the product measurements on each product page.'); ?></p>
            <div class="size-tabs">
                <a href="#tops">Tops</a>
                <a href="#pants">Pants</a>
                <a href="#dresses">Dresses</a>
                <a href="#outer">Outer</a>
                <a href="#accessories">Accessories</a>
            </div>
        </div>

        <!-- TOPS CHART -->
        <div class="size-chart-block" id="tops">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_tops_title') ?: 'Tops, Shirts, Vests & Sleeveless Pieces'); ?></h3>
                <div class="unit">cm</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $tops_chart = get_field('size_tops_chart');
                if ($tops_chart):
                ?>
                <table>
                    <thead>
                        <tr><th>Label</th><th>Bust / Chest</th><th>Waist</th><th>Shoulder</th><th>Fit Feel</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tops_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['bust']); ?></td>
                            <td><?php echo esc_html($row['waist']); ?></td>
                            <td><?php echo esc_html($row['shoulder']); ?></td>
                            <td><?php echo esc_html($row['fit_feel']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust / Chest</th><th>Waist</th><th>Shoulder</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <tr><td>XXS</td><td>76–80</td><td>58–62</td><td>34–35</td><td>Very close</td></tr>
                        <tr><td>XS</td><td>81–86</td><td>63–68</td><td>35–36</td><td>Close and neat</td></tr>
                        <tr><td>S</td><td>87–92</td><td>69–74</td><td>36–37</td><td>Balanced</td></tr>
                        <tr><td>M</td><td>93–98</td><td>75–81</td><td>38–39</td><td>Easy fit</td></tr>
                        <tr><td>L</td><td>99–106</td><td>82–89</td><td>40–41</td><td>Relaxed</td></tr>
                        <tr><td>XL</td><td>107–114</td><td>90–98</td><td>42–43</td><td>Generous</td></tr>
                        <tr><td>XXL</td><td>115–122</td><td>99–107</td><td>44–45</td><td>Full relaxed</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_tops_note') ?: 'A good fit should sit comfortably on the shoulders and rest across the chest without pulling.'); ?></p>
        </div>

        <!-- BRALETTES CHART -->
        <div class="size-chart-block">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_bralettes_title') ?: 'Bralettes & Close-Fit Tops'); ?></h3>
                <div class="unit">cm</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $bralettes_chart = get_field('size_bralettes_chart');
                if ($bralettes_chart):
                ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust</th><th>Underbust</th><th>Fit Feel</th><th>Best For</th></tr></thead>
                    <tbody>
                        <?php foreach ($bralettes_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['bust']); ?></td>
                            <td><?php echo esc_html($row['underbust']); ?></td>
                            <td><?php echo esc_html($row['fit_feel']); ?></td>
                            <td><?php echo esc_html($row['best_for']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust</th><th>Underbust</th><th>Fit Feel</th><th>Best For</th></tr></thead>
                    <tbody>
                        <tr><td>XS</td><td>76–82</td><td>62–68</td><td>Secure</td><td>Smaller frame</td></tr>
                        <tr><td>S</td><td>83–89</td><td>69–75</td><td>Comfortably close</td><td>Balanced fit</td></tr>
                        <tr><td>M</td><td>90–96</td><td>76–82</td><td>Natural support</td><td>Easy fit</td></tr>
                        <tr><td>L</td><td>97–104</td><td>83–90</td><td>Relaxed close</td><td>More room</td></tr>
                        <tr><td>XL</td><td>105–112</td><td>91–98</td><td>Soft relaxed</td><td>Generous feel</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_bralettes_note') ?: 'These pieces should feel secure but not tight. The fabric should sit gently on the body without pressure.'); ?></p>
        </div>

        <!-- PANTS CHART -->
        <div class="size-chart-block" id="pants">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_pants_title') ?: 'Pants, Trousers & Shorts'); ?></h3>
                <div class="unit">cm</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $pants_chart = get_field('size_pants_chart');
                if ($pants_chart):
                ?>
                <table>
                    <thead><tr><th>Label</th><th>Waist</th><th>Hip</th><th>Inseam</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <?php foreach ($pants_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['waist']); ?></td>
                            <td><?php echo esc_html($row['hip']); ?></td>
                            <td><?php echo esc_html($row['inseam']); ?></td>
                            <td><?php echo esc_html($row['fit_feel']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Label</th><th>Waist</th><th>Hip</th><th>Inseam</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <tr><td>XXS</td><td>58–62</td><td>82–86</td><td>68–70</td><td>Very close</td></tr>
                        <tr><td>XS</td><td>63–68</td><td>87–92</td><td>69–71</td><td>Close</td></tr>
                        <tr><td>S</td><td>69–74</td><td>93–98</td><td>70–72</td><td>Balanced</td></tr>
                        <tr><td>M</td><td>75–81</td><td>99–105</td><td>71–73</td><td>Easy fit</td></tr>
                        <tr><td>L</td><td>82–89</td><td>106–113</td><td>72–74</td><td>Relaxed</td></tr>
                        <tr><td>XL</td><td>90–98</td><td>114–122</td><td>73–75</td><td>Generous</td></tr>
                        <tr><td>XXL</td><td>99–107</td><td>123–131</td><td>74–76</td><td>Full relaxed</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_pants_note') ?: 'Choose your size mainly by hip measurement. The garment should feel easy when you sit, walk, and move.'); ?></p>
        </div>

        <!-- DRESSES CHART -->
        <div class="size-chart-block" id="dresses">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_dresses_title') ?: 'Dresses, Skirts & One-Piece Garments'); ?></h3>
                <div class="unit">cm</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $dresses_chart = get_field('size_dresses_chart');
                if ($dresses_chart):
                ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust</th><th>Waist</th><th>Hip</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <?php foreach ($dresses_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['bust']); ?></td>
                            <td><?php echo esc_html($row['waist']); ?></td>
                            <td><?php echo esc_html($row['hip']); ?></td>
                            <td><?php echo esc_html($row['fit_feel']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust</th><th>Waist</th><th>Hip</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <tr><td>XS</td><td>81–86</td><td>63–68</td><td>87–92</td><td>Close</td></tr>
                        <tr><td>S</td><td>87–92</td><td>69–74</td><td>93–98</td><td>Balanced</td></tr>
                        <tr><td>M</td><td>93–98</td><td>75–81</td><td>99–105</td><td>Easy fit</td></tr>
                        <tr><td>L</td><td>99–106</td><td>82–89</td><td>106–113</td><td>Relaxed</td></tr>
                        <tr><td>XL</td><td>107–114</td><td>90–98</td><td>114–122</td><td>Generous</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_dresses_note') ?: 'Follow the measurement that needs the most room: bust, waist, or hip. The garment should fall naturally without pulling.'); ?></p>
        </div>

        <!-- OUTER LAYERS CHART -->
        <div class="size-chart-block" id="outer">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_outer_title') ?: 'Outer Layers, Ponchos & Wraps'); ?></h3>
                <div class="unit">cm</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $outer_chart = get_field('size_outer_chart');
                if ($outer_chart):
                ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust / Chest</th><th>Shoulder</th><th>Best Use</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <?php foreach ($outer_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['bust']); ?></td>
                            <td><?php echo esc_html($row['shoulder']); ?></td>
                            <td><?php echo esc_html($row['best_use']); ?></td>
                            <td><?php echo esc_html($row['fit_feel']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Label</th><th>Bust / Chest</th><th>Shoulder</th><th>Best Use</th><th>Fit Feel</th></tr></thead>
                    <tbody>
                        <tr><td>XS/S</td><td>81–92</td><td>35–37</td><td>Light layer</td><td>Neat relaxed</td></tr>
                        <tr><td>S/M</td><td>87–98</td><td>36–39</td><td>Everyday layer</td><td>Balanced drape</td></tr>
                        <tr><td>M/L</td><td>93–106</td><td>38–41</td><td>Outer layer</td><td>Easy relaxed</td></tr>
                        <tr><td>L/XL</td><td>99–114</td><td>40–43</td><td>Roomier layer</td><td>Generous</td></tr>
                        <tr><td>Free Size</td><td>Flexible</td><td>Flexible</td><td>Poncho / wrap</td><td>Flowing</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_outer_note') ?: 'Outer layers are made for comfort and movement. Size up if you want to layer over another garment.'); ?></p>
        </div>

        <!-- ACCESSORIES CHART -->
        <div class="size-chart-block" id="accessories">
            <div class="size-chart-head">
                <h3><?php echo esc_html(get_field('size_accessories_title') ?: 'Shawls, Scarves, Bags, Towels & Slippers'); ?></h3>
                <div class="unit">finished dimensions</div>
            </div>
            <div class="size-table-wrap">
                <?php 
                $accessories_chart = get_field('size_accessories_chart');
                if ($accessories_chart):
                ?>
                <table>
                    <thead><tr><th>Product</th><th>Guide</th><th>Best For</th><th>Fit Note</th></tr></thead>
                    <tbody>
                        <?php foreach ($accessories_chart as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['product']); ?></td>
                            <td><?php echo esc_html($row['guide']); ?></td>
                            <td><?php echo esc_html($row['best_for']); ?></td>
                            <td><?php echo esc_html($row['fit_note']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table>
                    <thead><tr><th>Product</th><th>Guide</th><th>Best For</th><th>Fit Note</th></tr></thead>
                    <tbody>
                        <tr><td>Scarf</td><td>30–45 cm wide</td><td>Neck styling</td><td>Light and easy</td></tr>
                        <tr><td>Shawl / Sal</td><td>60–90 cm wide</td><td>Shoulder wrap</td><td>Larger gives more coverage</td></tr>
                        <tr><td>Crossbody Bag</td><td>By strap drop</td><td>Daily carry</td><td>Check product page</td></tr>
                        <tr><td>Towel</td><td>By use</td><td>Face, hand, body</td><td>Check dimensions</td></tr>
                        <tr><td>Slippers</td><td>Foot length</td><td>Indoor comfort</td><td>Size up if between sizes</td></tr>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            <p class="size-note"><?php echo esc_html(get_field('size_accessories_note') ?: 'For accessories, check the product dimensions. For shawls and scarves, choose by coverage. For bags, check strap length and size. For slippers, choose by foot length and size up if between sizes.'); ?></p>
        </div>
    </section>

    <!-- GALLERY SECTION -->
    <section class="size-gallery">
        <?php 
        $gallery_images = get_field('size_gallery');
        if ($gallery_images && is_array($gallery_images)):
            foreach ($gallery_images as $img):
        ?>
        <div class="size-gallery-item" style="background-image: url('<?php echo esc_url($img['image']); ?>');"></div>
        <?php 
            endforeach;
        else:
        ?>
        <div class="size-gallery-item g1" style="background-image: url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=1200&q=80');"></div>
        <div class="size-gallery-item g2" style="background-image: url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80');"></div>
        <div class="size-gallery-item g3" style="background-image: url('https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1000&q=80');"></div>
        <?php endif; ?>
    </section>

    <!-- MEASUREMENT GUIDE SECTION -->
    <section class="size-section size-split">
        <div class="size-photo size-photo-measure" style="background-image: url('<?php echo esc_url(get_field('size_measure_image') ?: 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?auto=format&fit=crop&w=1300&q=80'); ?>');"></div>
        <div>
            <div class="eyebrow"><?php echo esc_html(get_field('size_measure_eyebrow') ?: 'Measurement Guide'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_measure_title') ?: 'Measurement Guide'); ?></h2>
            <div class="size-measure-list">
                <?php 
                $measurements = get_field('size_measurements');
                if ($measurements):
                    foreach ($measurements as $m):
                ?>
                <div class="size-measure-row">
                    <strong><?php echo esc_html($m['label']); ?></strong>
                    <span><?php echo esc_html($m['description']); ?></span>
                </div>
                <?php 
                    endforeach;
                else:
                ?>
                <div class="size-measure-row"><strong>Bust / Chest</strong><span>Measure around the fullest part of your chest.</span></div>
                <div class="size-measure-row"><strong>Waist</strong><span>Measure around the narrowest part of your waist.</span></div>
                <div class="size-measure-row"><strong>Hip</strong><span>Measure around the fullest part of your hips.</span></div>
                <div class="size-measure-row"><strong>Inseam</strong><span>Measure from the inner leg to the ankle.</span></div>
                <div class="size-measure-row"><strong>Length</strong><span>Compare with a garment you already like wearing.</span></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FIT FEELING SECTION -->
    <section class="size-section alt">
        <div class="size-center">
            <div class="eyebrow"><?php echo esc_html(get_field('size_fit_eyebrow') ?: 'Fit Feeling'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_fit_title') ?: 'Fit Feeling'); ?></h2>
        </div>
        <div class="size-fit-guide">
            <?php 
            $fit_types = get_field('size_fit_types');
            if ($fit_types):
                foreach ($fit_types as $fit):
            ?>
            <div class="size-fit-card">
                <h3><?php echo esc_html($fit['title']); ?></h3>
                <p><?php echo esc_html($fit['description']); ?></p>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="size-fit-card"><h3>Close</h3><p>Best for bralettes and fitted pieces.</p></div>
            <div class="size-fit-card"><h3>Balanced</h3><p>Your usual size. Comfortable, clean, and natural.</p></div>
            <div class="size-fit-card"><h3>Relaxed</h3><p>More room, more movement, and a softer drape.</p></div>
            <?php endif; ?>
        </div>
    </section>

    <!-- HANDMADE VARIATION SECTION -->
    <section class="size-section size-split">
        <div>
            <div class="eyebrow"><?php echo esc_html(get_field('size_handmade_eyebrow') ?: 'Handmade Variation'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_handmade_title') ?: 'Every LOOMAAMA piece is made through hand-guided work.'); ?></h2>
            <p><?php echo wp_kses_post(get_field('size_handmade_text') ?: 'Small differences in size, texture, and fall are natural. These are not flaws. They are part of handmade nettle cloth.'); ?></p>
        </div>
        <div class="size-photo size-photo-fit" style="background-image: url('<?php echo esc_url(get_field('size_handmade_image') ?: 'https://images.unsplash.com/photo-1506629905607-d9e297d2c88d?auto=format&fit=crop&w=1300&q=80'); ?>');"></div>
    </section>

    <!-- HELP SECTION -->
    <section class="size-help bg-card">
        <div class="size-center">
            <div class="eyebrow"><?php echo esc_html(get_field('size_help_eyebrow') ?: 'Personal Fit Help'); ?></div>
            <h2><?php echo wp_kses_post(get_field('size_help_title') ?: 'Still unsure?'); ?></h2>
            <p><?php echo wp_kses_post(get_field('size_help_text') ?: 'Send us your measurements and the product name. We will help you choose the best size for your body and the garment.'); ?></p>
        </div>
        <div class="size-help-grid">
            <?php 
            $help_cards = get_field('size_help_cards');
            if ($help_cards):
                foreach ($help_cards as $card):
            ?>
            <div class="size-help-card">
                <h3><?php echo esc_html($card['title']); ?></h3>
                <p><?php echo esc_html($card['description']); ?></p>
                <a class="size-button" href="<?php echo esc_url($card['button_link']); ?>"><?php echo esc_html($card['button_text']); ?></a>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="size-help-card"><h3>Chat</h3><p>Quick fit advice before ordering.</p><a class="size-button" href="#">Chat Now</a></div>
            <div class="size-help-card"><h3>Email</h3><p>Send size, height, and product name.</p><a class="size-button" href="#">Email Us</a></div>
            <div class="size-help-card"><h3>Product Notes</h3><p>Check exact garment measurements.</p><a class="size-button" href="#">View Products</a></div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>