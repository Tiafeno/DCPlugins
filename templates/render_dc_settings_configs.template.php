<?php
/**
 * Created by PhpStorm.
 * User: Tiafeno
 * Date: 22/08/2017
 * Time: 20:25
 */

?>
<div class="uk-section ">
	<div class="uk-container uk-container-small">
		<?php foreach ($configs as $_config): $config = (object)$_config;
			?>

		<form id="id_<?= $config->type ?>" action="<?= admin_url('admin.php?page=dc_configs') ?>" method="post" onsubmit="return false"  class="uk-form-small uk-margin ">
			<?php wp_nonce_field( 'configs_add_nonce', 'configs_nonce' ); ?>
			<div  uk-grid>
				<input type="hidden" name="post_type" value="<?= $config->type ?>">
				<div class="uk-width-1-3">
					<label class="uk-form-label" for="form-horizontal-select"><?= $config->label ?></label>
				</div>
				<div class="uk-width-1-3">
					<div class="uk-form-controls ">
						<select class="uk-select " id="form-horizontal-select" name="page_id">
							<option value=""></option>
							<?php
							if ($pages->have_posts()):
								while ($pages->have_posts()): $pages->the_post();
									$id = (int)$this->getSettings('page_id', ['post_type', $config->type]);
									?>
									<option value="<?= $pages->post->ID ?>" <?php if($id == (int)$pages->post->ID):  ?> selected <?php endif; ?>>
										<?= $pages->post->post_title ?>
									</option>
									<?php
								endwhile;
							endif;
							?>
						</select>
					</div>
				</div>
				<div class="uk-width-1-3">
					<input type="submit" data-form="id_<?= $config->type ?>" class="uk-button uk-button-primary">
					<div uk-spinner class="uk-hidden"></div>
					<span id="log" class="uk-label uk-label-success uk-hidden">Success</span>
				</div>
			</div>
		</form>
			<hr class="uk-divider-icon">
		<?php endforeach; ?>
	</div>
</div>

