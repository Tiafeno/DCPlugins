<fieldset>
    <input type="radio" name="content_type" class="post-format" value="0" 
      <?= (empty( $content_type )) ? ' checked="checked" ': '' ?>
      id="content-type-0"></input>
    <label for="content-type-0" class="post-format-icon">
      DEFAULT
    </label>
    <br>
  <?php foreach ($this->PostTypes as $posttype): ?>
    <input type="radio" name="content_type" class="post-format" value="<?= $posttype[ 'type' ] ?>" 
      <?= (!empty( $content_type ) && $content_type == $posttype[ 'type' ]) ? ' checked="checked" ': '' ?>
      id="<?= 'content-type-'.$posttype[ 'type' ] ?>"></input>
    <label for="<?= 'content-type-'.$posttype[ 'type' ] ?>" class="post-format-icon">
      <?= strtoupper($posttype[ 'label' ]) ?>
    </label>
    <br>
  <?php endforeach; ?>
</fieldset>