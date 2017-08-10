<section>
  <label><?php wp_nonce_field( 'fw_metabox_nonce', 'fw_nonce' ); ?></label>
  <label>
    <input type="checkbox" id="favorite_works" name="favorite_works" <?= ( (int)$favorite_works_value == 1 ) ? 'checked' : '' ?> 
    value="<?= ( (int)$favorite_works_value ) ? (int)$favorite_works_value : 1 ?>">
    Favorite Works
  </label>
</section>