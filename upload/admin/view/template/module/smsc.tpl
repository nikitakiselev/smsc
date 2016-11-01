<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-smsc" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i> <?php print $button_save; ?></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <?php if (count($errors)): ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <strong>Настройки не сохранены!</strong> Некоторые поля заполнены неверно. Пройдитесь по вкладкам настроек и исправьте поля, которые выделены красным.
            </div>
        <?php endif; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">

                <?php if (! $smsc_status): ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-info-circle"></i>
                        <?php print $module_disabled_notification; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-smsc" class="form-horizontal">

                    <div class="tab-pane">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active">
                                <a href="#main"  data-toggle="tab">
                                    <i class="fa fa-cogs"> </i> Основное
                                </a>
                            </li>
                            <li>
                                <a href="#provider" data-toggle="tab">
                                    <i class="fa fa-envelope"></i> Настройки провайдера
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="main">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-10">
                                        <select name="smsc_status" id="input-status" class="form-control">

                                            <option value="active"<?php print $smsc_status=='active' ? ' selected': ''; ?>><?php echo $text_enabled; ?></option>
                                            <option value="demo"<?php print $smsc_status=='demo' ? ' selected': ''; ?>><?php echo $text_demo; ?></option>
                                            <option value="disabled"<?php print $smsc_status=='disabled' ? ' selected': ''; ?>><?php echo $text_disabled; ?></option>
                                        </select>

                                        <div id="smsc-status-demo-info-block" class="help-block" style="display: none;">
                                            <div  class="alert alert-info">
                                                <i class="fa fa-info-circle"></i>
                                                <?php print $demo_mode_description; ?> <strong><?php print $config_mail; ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="phones">
                            <span data-toggle="tooltip"
                                  data-html="true"
                                  data-trigger="click"
                                  title="<?php echo htmlspecialchars($help_phones); ?>"
                            >
                                <?php echo $entry_phones; ?>
                            </span>
                                    </label>

                                    <div class="col-sm-10">
                                        <textarea name="smsc_phones" rows="5" id="phones" class="form-control" placeholder="<?php print $placeholder_phones; ?>"><?php echo $smsc_phones; ?></textarea>
                                        <?php if ($error_smsc_phones) { ?>
                                            <div class="text-danger"><?php echo $error_smsc_phones; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="template">
                                        <span data-toggle="tooltip"
                                              data-html="true"
                                              data-trigger="click"
                                              title="<?php echo htmlspecialchars($help_template); ?>"
                                        >
                                            <?php echo $entry_template; ?>
                                        </span>
                                    </label>

                                    <div class="col-sm-10">
                                        <textarea name="smsc_template" rows="5" id="template" class="form-control" placeholder="<?php print $placeholder_template; ?>"><?php echo $smsc_template; ?></textarea>
                                        <div class="help-block">
                                            <a href="#tokensModal"
                                               data-toggle="modal"
                                               data-target="#tokensModal"
                                               class="btn btn-info btn-sm"
                                            >
                                                <?php print $show_tokens_link_text; ?>
                                            </a>
                                        </div>
                                        <?php if ($error_smsc_template) { ?>
                                        <div class="text-danger"><?php echo $error_smsc_template; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="provider">

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="fa fa-question-circle"></i> Где взять логин и пароль?
                                        </button>
                                        <div class="collapse" id="collapseExample">
                                            <br>
                                            <div class="well">
                                                <p>Логин и пароль необходимо вводить те, которые вы вводили при регистрации на сайте  <a href="https://smsc.ru" target="_blank">https://smsc.ru</a>.</p>
                                                <p>Если Вы не зарегистрированы на сайте, но необходимо зарегистрироваться по ссылке: <a href="https://smsc.ru/reg/" target="_blank">https://smsc.ru/reg/</a></p>
                                                Система не хранит ваш пароль в явном виде, а хранит только md5 хеш от него, поэтому за сохранность пароля Вы можете не беспокоиться.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-sender-name">
                                        <?php echo $entry_sender_name; ?>
                                    </label>

                                    <div class="col-sm-10">
                                        <input type="text"
                                               name="smsc_sender_name"
                                               value="<?php echo $smsc_sender_name; ?>"
                                               id="input-sender-name"
                                               class="form-control"
                                        />

                                        <div class="help-block">
                                            <a href="#senderIdModal" data-toggle="modal" data-target="#senderIdModal">
                                                <i class="fa fa-warning"></i> Как правильно выбрать имя отправителя?
                                            </a>
                                        </div>

                                        <?php if ($error_smsc_sender_name) { ?>
                                            <div class="text-danger"><?php echo $error_smsc_sender_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-login">
                                        <?php echo $entry_login; ?>
                                    </label>

                                    <div class="col-sm-10">
                                        <input type="text"
                                               name="smsc_login"
                                               value="<?php echo $smsc_login; ?>"
                                               id="input-login"
                                               class="form-control"
                                               autocomplete="off"
                                        />

                                        <?php if ($error_smsc_login) { ?>
                                            <div class="text-danger"><?php echo $error_smsc_login; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-password">
                                        <?php echo $entry_password; ?>
                                    </label>

                                    <div class="col-sm-10">
                                        <input type="password"
                                               name="smsc_password"
                                               value="<?php echo $smsc_password; ?>"
                                               id="input-password"
                                               class="form-control"
                                               autocomplete ="off"
                                        />

                                        <div class="help-block">В целях безопасности, после сохранения, пароль будет заменён на его md5 хеш.</div>

                                        <?php if ($error_smsc_password) { ?>
                                            <div class="text-danger"><?php echo $error_smsc_password; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-charset">
                                        <?php echo $entry_charset; ?>
                                    </label>

                                    <div class="col-sm-10">
                                        <select name="smsc_charset" id="input-charset" class="form-control">
                                            <?php foreach(array('windows-1251', 'utf-8', 'koi8-r') as $charset): ?>
                                                <option value="<?php print $charset; ?>"<?php print $smsc_charset=== $charset ? ' selected': ''; ?>>
                                                    <?php print strtoupper($charset); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <?php if ($error_smsc_charset) { ?>
                                        <div class="text-danger"><?php echo $error_smsc_charset; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel-footer">
            <i class="fa fa-user"></i> <?php print $author_label; ?>:
            <a href="mailto:mail@nikitakiselev.ru">Никита Киселев</a>&nbsp;
            <i class="fa fa-envelope"></i> e-mail:
            <a href="mailto:mail@nikitakiselev.ru">mail@nikitakiselev.ru</a>&nbsp;
            <i class="fa fa-external-link"></i> <?php print $site_label; ?>:
            <a href="https://nikitakiselev.ru" target="_blank">nikitakiselev.ru</a>
        </div>
    </div>
</div>

<!-- Token list modal -->
<div class="modal fade" id="tokensModal" tabindex="-1" role="dialog" aria-labelledby="tokensModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tokensModalLabel"><?php print $token_modal_title; ?></h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Токен</th>
                        <th>Описание значения</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($tokens as $token => $description): ?>
                    <tr>
                        <td><span class="label label-default"><?php print $token; ?></span></td>
                        <td><?php print $description; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php print $close_btn_text; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sender name help -->
<div class="modal fade" id="senderIdModal" tabindex="-1" role="dialog" aria-labelledby="senderIdModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="senderIdModalLabel">Как правильно выбрать имя отправителя?</h4>
            </div>
            <div class="modal-body">
                <p><strong>Имя отправителя</strong> - это имя, которое будет указываться в отправителе SMS-сообщений.</p>
                <p>Но не всё так просто, как кажется. Вы не можете вводить в это поле любое имя, а только то, которое добавлено в список разрешённых имён в своей панели управления на сайте <a href="https://smsc.ru/senders/" target="_blank">https://smsc.ru/senders/</a></p>
                <p>Модераторы должны одобрить это имя, только после этого Вы можете вписать его в это поле.</p>
                <p>Эта манипуляция гарантирует Вам то, что сообщения, отправляемые с сайта не будут запрещены спам фильтром.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php print $close_btn_text; ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#input-status').on('change', function () {
        $('#smsc-status-demo-info-block').hide();

        if ($(this).val() === 'demo') {
            $('#smsc-status-demo-info-block').fadeIn();
        }
    }).trigger('change');
</script>
<?php echo $footer; ?>