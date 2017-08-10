<h2 class="uk-heading-bullet">EXPERIENCES</h2>
<div class="uk-grid-match uk-grid-small" uk-grid>
    <div class="uk-width-1-3@m">
      <div class="uk-card uk-card-default uk-card-body">
        <form method="post" action="">
          <h4 class="uk-heading-bullet">Add new experiences</h4>
          <?php wp_nonce_field( 'experiences_add_nonce', 'exp_nonce' ); ?>
          <input type="hidden" name="vendor" value="action_add_experiences">
          <div class="uk-margin">
              <div class="uk-inline">
                <input required class="uk-input" name="company_name" placeholder="Company name" type="text">
              </div>
          </div>
          <div class="uk-margin">
            <textarea required class="uk-textarea" rows="5" name="company_description" placeholder="Company description"></textarea>
          </div>
          
          <div class="uk-margin">
            <div class='image-preview-wrapper'>
              <img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px; padding-bottom: 10px'>
            </div>
            <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload logo' ); ?>" />
            <input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>
          </div>
          <div class="uk-margin" uk-margin>
            <button type="submit" class="uk-button uk-button-primary">add</button>
          </div>
        </form>
      </div>
    </div>
    <div class="uk-width-2-3@m">
        <div class="uk-card uk-card-default uk-card-body">
          <div class="uk-description-list uk-description-list-divider"  uk-grid>
            <?php 
                while(list(, $experience) = each($this->Experiences)):
                  $url = ($experience->logo == null) ? "#{$experience->name}" : wp_get_attachment_image_src($experience->logo, 'medium')[0];
             ?>
                <div class="uk-card-media-left uk-cover-container uk-width-1-3">
                  <img src="<?= $url ?>" alt="<?= $experience->name ?>" style="padding-left: 10px;" uk-cover>
                  <canvas width="40" height="40"></canvas>
                </div>
                <div class="uk-width-2-3">
                  <div class="uk-card-body">
                    <dt><?= $experience->name ?></dt>
                    <dd><?= $experience->description ?></dd>
                    <a class="uk-button uk-button-danger uk-button-small" href="?page=dc_settings&vendor=action_delete_experiences&id=<?= $experience->id ?>" >Delete</a>
                  </div>
                </div>
           <?php endwhile; ?>
          </div>
        </div>
    </div>
</div>

<div class="uk-margin-medium-top" >
    <ul class="uk-flex-center" uk-tab>
        <li class="uk-active"><a href="#">Center</a></li>
        <li><a href="#">Item</a></li>
        <li><a href="#">Item</a></li>
    </ul>
    <ul class="uk-switcher uk-margin">
        <li>
          <dl class="uk-description-list uk-description-list-divider">
              <div uk-alert>
                <dt>Description term</dt>
                <dd>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</dd>
                <a class="uk-button uk-button-danger uk-button-small" href="#modal" uk-toggle>Favorite</a>
              </div>
              <dt>Description term</dt>
              <dd>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</dd>
              <dt>Description term</dt>
              <dd>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</dd>
          </dl>
        </li>
        <li>
          <dl class="uk-description-list uk-description-list-divider">
            <div uk-alert>
              <dt>Description term</dt>
              <dd>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</dd>
              <a class="uk-button uk-button-primary uk-button-small" href="#modal" uk-toggle>Favorite</a>
            </div>
          </dl>
        </li>
        <li>Bazinga!</li>
    </ul>
</div>

