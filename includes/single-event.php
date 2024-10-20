<style>
main {
    display: flex;
    width: 100%;
}

.event-page {
    display: flex;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 8px 5px #00000010;
    width: 800px;
    overflow: hidden;
    margin: 0 auto;

}

.event-page picture {
    width: 60%;
    height: 100%;
    max-height: 275px;
    position: relative;
    overflow: hidden;
}

.event-page picture img {
    width: 105%;
    height: 100%;
}

.event-page picture .categories {
    position: absolute;
    top: 10px;
    left: 10px;
    color: #fff;
    font-size: 12px;
    text-transform: uppercase;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
}

.event-page picture .categories span {
    background-color: #3490dc;
    padding: 3px 10px;
    border-radius: 5px;
}

.event-page picture .details {
    position: absolute;
    display: flex;
    justify-content: space-around;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.44);
    /* #00000070 en notación rgba */
    padding: 20px 0;
    z-index: 100;
}

.event-page picture .details span {
    font-size: 12px;
    color: #fff;
}

.event-page aside {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 40%;
    padding: 20px;
}

.event-page aside h3 {
    margin: 0;
    font-size: 22px;
    font-weight: bold;
}

.event-page aside span {
    font-size: 14px;
}

.event-page aside span p {
    margin: 0;
}

.event-page aside div {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.event-page aside div div {
    flex-direction: row;
}

.event-page aside .btn {
    padding: 5px 8px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
    transition: background-color 0.3s ease;
}

.event-page aside .btn--info {
    background-color: #3490dc;
    color: #fff;
}

.event-page aside .btn--primary {
    background-color: #38c172;
    color: #fff;
}

.event-page aside .btn:hover {
    opacity: 0.8;
}
</style>

<main>
    <div class="event-page">
        <picture>
            <img src="<?php echo esc_html(get_post_meta(get_the_ID(), '_event_thumbnail', true)); ?>" alt="placeholder">
            <div class="categories">
                <?php 
            $categories = get_the_terms(get_the_ID(), 'category'); 
            if ($categories && !is_wp_error($categories)) : ?>
                <?php foreach ($categories as $category) : ?>
                <span class="category-tag"><?php echo esc_html($category->name); ?></span>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="details">
                <span><i class="fas fa-map-marker-alt"></i>
                    <?php echo esc_html(get_post_meta(get_the_ID(), '_event_location', true)); ?>
                </span>

                <span><i class="fas fa-calendar-alt"></i>
                    <?php echo esc_html(get_post_meta(get_the_ID(), '_event_date', true)); ?>
                </span>
                <span><i class="fas fa-clock"></i>
                    <?php echo esc_html(get_post_meta(get_the_ID(), '_event_time', true)); ?>
                </span>

            </div>
        </picture>
        <aside>
            <div>
                <h3><?php the_title(); ?></h3>
                <span><?php the_excerpt(); ?></span>
            </div>

            <div>

                <div>

                    <span>
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_event_price', true)); ?> €
                    </span>
                    <span><i class="fas fa-ticket-alt"></i>
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_tickets_left', true)); ?> Libres
                    </span>

                </div>
                <a href="<?php the_permalink(); ?>" class="btn btn--info">Más información</a>
                <a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_event_url_tickets', true)); ?>"
                    target="_blank" class="btn btn--primary">Comprar Entradas</a>
            </div>
        </aside>
    </div>
</main>