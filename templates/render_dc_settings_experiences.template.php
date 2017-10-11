<h2 class="uk-heading-bullet">EXPERIENCES</h2>
<div uk-grid style="height: inherit; background: #f1f1f1;">
  <div class="uk-width-1-2@m">
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

    <div>
      <div uk-grid>
      <?php 
          while(list(, $experience) = each($this->Experiences)):
            $url = ($experience->logo == null) ? null : wp_get_attachment_image_src($experience->logo, 'medium')[0];
        ?>
          <div class="uk-width-1-3">
          <?php if (!is_null( $url )): ?> 
            <div class="uk-cover-container" style="margin-top: 35px;">
              <canvas width="200" height="200"></canvas>
              <img src="<?= $url ?>" alt="<?= $experience->name ?>" style=""  uk-cover>
            </div>
          <?php endif; ?>
          </div>
          <div class="uk-width-2-3">
            <div class="uk-card uk-card-body">
              <dt><?= $experience->name ?></dt>
              <dd><?= $experience->description ?></dd>
              <a class="uk-button uk-button-danger uk-button-small" href="?page=dc_settings&vendor=action_delete_experiences&id=<?= $experience->id ?>" >Delete</a>
            </div>
          </div>
      <?php endwhile; ?>
      </div>
    </div>
  </div>
  <!--
  <div class="uk-width-1-2@m">
    <div class="uk-card uk-card-default uk-card-body">
      <form method="post" action="">
        <h4 class="uk-heading-bullet">SKILLS</h4>
        <?php wp_nonce_field( 'skills_experiences_add_nonce', 'skills_nonce' ); ?>
        <input type="hidden" name="vendor" value="action_add_option_experiences">
        <div class="uk-margin">
          <?php
            $skill = (get_option('skills')) ? get_option('skills') : '';
          ?>
          <textarea class="uk-textarea" rows="5" name="skills" placeholder="On skill per lines"><?= $skill ?></textarea>
        </div>
        <div class="uk-margin" uk-margin>
          <button type="submit" class="uk-button uk-button-primary">add</button>
        </div>
      </form>
    </div>
    <div class="uk-card uk-card-default uk-card-body">
      <form method="post" action="">
        <h4 class="uk-heading-bullet">AWARDS</h4>
        <?php wp_nonce_field( 'awards_experiences_add_nonce', 'awards_nonce' ); ?>
        <input type="hidden" name="vendor" value="action_add_option_experiences">
        <?php
            $awards = (get_option('awards')) ? get_option('awards') : '';
          ?>
        <div class="uk-margin">
          <textarea class="uk-textarea" rows="5" name="awards" placeholder="On award per lines"><?= $awards ?></textarea>
        </div>
        <div class="uk-margin" uk-margin>
          <button type="submit" class="uk-button uk-button-primary">add</button>
        </div>
      </form>
    </div>
  </div>
  -->

</div>