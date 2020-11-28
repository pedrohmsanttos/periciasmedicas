<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <?php
                $temPermissaoUsuario = Util::temPermissao("Menu.Usuario");
                $temPermissaoPerfil = Util::temPermissao("Menu.Perfil") && Util::temPermissao("Perfil.consultar");
                ?>
                <?php
                if ($temPermissaoUsuario || $temPermissaoPerfil):
                    ?>
                    <li class="sub-menu">
                        <a href="javascript:;"> 
                            <i class="fa fa-people"></i> <span><?php echo __('menu_administrativo'); ?></span>
                        </a>
                        <ul class="sub">
                            <li class="sub-menu"><a href="#"><?php echo __('menu_controle_acesso'); ?></a>
                                <ul class="sub sub3">
                                    <?php
                                    if ($temPermissaoUsuario):
                                        ?>
                                        <li class="sub-menu">
                                            <?php
                                            echo $this->Html->link(__("menu_usuarios"), array(
                                                'controller' => 'usuario',
                                                'action' => 'index'
                                            ));
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php
                                    if ($temPermissaoPerfil):
                                        ?>
                                        <li class="sub-menu" >
                                            <?php
                                            echo $this->Html->link(__("menu_perfil"), array(
                                                'controller' => 'Perfil',
                                                'action' => 'index'
                                            ));
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php
                                    if (true):
                                        ?>
                                        <li class="sub-menu" >
                                            <?php
                                            echo $this->Html->link(__("menu_auditoria"), array(
                                                'controller' => 'Auditoria',
                                                'action' => 'index'
                                            ));
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                </ul></li>
                        </ul></li>
                    <?php
                endif;
                ?>
                <?php
                $temPermissaoCargo = Util::temPermissao("Menu.Cargo") && Util::temPermissao("Cargo.consultar");
                $temPermissaoEmpresa = Util::temPermissao("Menu.Empresa") && Util::temPermissao("Empresa.consultar");
                $temPermissaoFuncao = Util::temPermissao("Menu.Funcao") && Util::temPermissao("Funcao.consultar");
                $temPermissaoOrgaoOrigem = Util::temPermissao("Menu.OrgaoOrigem") && Util::temPermissao("OrgaoOrigem.consultar");
                $temPermissaoTipologia = Util::temPermissao("Menu.Tipologia") && Util::temPermissao("Tipologia.consultar");
                $temPermissaoEspecialidade = Util::temPermissao("Menu.Especialidade") && Util::temPermissao("Especialidade.consultar");
                $temPermissaoLotacao = Util::temPermissao("Menu.Lotacao") && Util::temPermissao("Lotacao.consultar");
                $temPermissaoCid = Util::temPermissao("Menu.Cid") && Util::temPermissao("Cid.consultar");
                $temPermissaoUnidadeAtendimento = Util::temPermissao("Menu.UnidadeAtendimento") && Util::temPermissao("UnidadeAtendimento.consultar");
                $temPermissaoParametroGeral = Util::temPermissao("Menu.ParametroGeral");
                $temPermissaoFeriados = Util::temPermissao("Feriado.consultar");
                $temPermissaoAgendaPerito = Util::temPermissao("Menu.AgendaPerito");
               
                $temPermissaoAgendaSistema = Util::temPermissao("Menu.AgendaSistema");
                ?>
                <?php
                if ($temPermissaoCargo || $temPermissaoEmpresa || $temPermissaoFuncao || $temPermissaoOrgaoOrigem || $temPermissaoTipologia ||
                        $temPermissaoEspecialidade || $temPermissaoLotacao || $temPermissaoCid || $temPermissaoUnidadeAtendimento || $temPermissaoParametroGeral
                        || $temPermissaoAgendaSistema || $temPermissaoAgendaPerito || $temPermissaoFeriados):
                    ?>
                    <li class="sub-menu"><a href="javascript:;"> <i class="fa fa-list-alt"></i> <span><?php echo __('menu_configuracao_sistema'); ?></span>
                        </a>
                        <ul class="sub">
                            <?php
                            if ($temPermissaoAgendaSistema): ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link("Agenda", array(
                                        'controller' => 'AgendaSistema',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoCargo):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_cargo"), array(
                                        'controller' => 'Cargo',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>    
                            <?php
                            if ($temPermissaoEmpresa):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_empresa"), array(
                                        'controller' => 'Empresa',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>           
                            <?php
                            if ($temPermissaoFuncao):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_funcao"), array(
                                        'controller' => 'Funcao',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>    
                            <?php
                            if ($temPermissaoOrgaoOrigem):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_orgao_origem"), array(
                                        'controller' => 'OrgaoOrigem',
                                        'action' => 'index'
                                    ));
                                    ?>  
                                </li>
                            <?php endif; ?>    
                            <?php
                            if ($temPermissaoTipologia):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_tipologia"), array(
                                        'controller' => 'tipologia',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoEspecialidade):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_especialidade"), array(
                                        'controller' => 'Especialidade',
                                        'action' => 'index'
                                    ));
                                    ?>  
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoLotacao):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_lotacao"), array(
                                        'controller' => 'Lotacao',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoCid):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_cid"), array(
                                        'controller' => 'Cid',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoUnidadeAtendimento):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_unidade_atendimento"), array(
                                        'controller' => 'UnidadeAtendimento',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoParametroGeral):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_parametro_geral"), array(
                                        'controller' => 'ParametroGeral',
                                        'action' => 'editar',
                                        1
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($temPermissaoFeriados):
                                ?>
                                <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link(__("menu_feriado"), array(
                                        'controller' => 'Feriado',
                                        'action' => 'index'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php
                endif;
                ?>
                <?php


                $tipoUsuario = CakeSession::read('Auth.User.tipo_usuario_id');
                if ((Util::temPermissao("Menu.Agendamento") && $tipoUsuario != USUARIO_PERITO_CREDENCIADO) || (in_array("Administrativo", $_SESSION['permissoesPerfil']))):
                    ?>
                    <li class="">
                        <?php
                        echo $this->Html->link('<i class="fa fa-calendar"></i>' . __("menu_agendamento"), array(
                            'controller' => 'Agendamento',
                            'action' => 'index'
                                ), array(
                            'escape' => false  //NOTICE THIS LINE ***************
                        ));
                        ?>
                    </li>
                <?php endif; ?>
                <?php
                if (Util::temPermissao("Menu.Atendimento") && $tipoUsuario != USUARIO_SERVIDOR):
                    ?>
                    <li class="sub-menu"> 
                        <a href="#"><i class="fa fa-book"></i><?php echo __('menu_atendimento'); ?></a>
                        <ul class="sub">
                            <?php
                            $labelMenu = "";
                            if ($tipoUsuario == USUARIO_INTERNO):
                                $labelMenu = __("menu_consultar");
                            else:
                                $labelMenu = __("menu_efetuar_atendimento");
                            endif;
                            ?>
                            <li class="sub-menu">
                                <?php
                                echo $this->Html->link($labelMenu, array(
                                    'controller' => 'Atendimento',
                                    'action' => 'index'
                                ));
                                ?>
                            </li>
                            <?php if ($tipoUsuario == USUARIO_PERITO_CREDENCIADO || $tipoUsuario == USUARIO_PERITO_SERVIDOR): ?>
                                <li class="sub-menu" >
                                    <?php
                                    echo $this->Html->link(__("menu_atendimentos_pendentes"), array(
                                        'controller' => 'Atendimento',
                                        'action' => 'indexAtendimentoPendentes'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php //if (CakeSession::read('Auth.User.cpf') == '00000000000'): ?>
                            <?php if (CakeSession::read('Auth.User.admin') == 'true'): ?>
                                <li class="sub-menu" >
                                    <?php
                                    echo $this->Html->link(__("Excluir Atendimento"), array(
                                        'controller' => 'Atendimento',
                                        'action' => 'indexExcluirAtendimento'
                                    ));
                                    ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php
                if (Util::temPermissao("Menu.Processo")):
                    ?>
                    <li class="sub-menu">
                        <a href="#"><i class="fa fa-book"></i><?php echo __('menu_processos'); ?></a>
                          <ul class="sub">
						  <?   
							$temPermissaoProcessoConsultar = Util::temPermissao("Processo.consultar"); 
							$temPermissaoProcessoNova = Util::temPermissao("Processo.nova"); 
							$temPermissaoProcessoLista = Util::temPermissao("Processo.lista");
                            $temPermissaoProcessoLaudo = Util::temPermissao("Processo.laudo"); 
						  ?>
						  <? if ($temPermissaoProcessoConsultar): ?>
                              <li class="sub-menu">
                                    <?php
                                    echo $this->Html->link('Consulta', array(
                                        'controller' => 'Atendimento',
                                        'action' => 'index_processos'
                                            ), array(
                                        'escape' => false
                                    ));
                                    ?>
                             </li>
							  <?php endif; ?>

                              <? if ($temPermissaoProcessoLaudo): ?>
                                 <li>
                                    <?php
                                    echo $this->Html->link('Laudos', array(
                                     'controller' => 'Atendimento',
                                     'action' => 'index_laudo'
                                    ), array(
                                     'escape' => false
                                    ));
                                    ?>
                                </li>
                                <?php endif; ?>

							   <? if ($temPermissaoProcessoNova || $temPermissaoProcessoLista): ?>
                             <li class="sub-menu">
                                 <a href="#"><i class="fa fa-book"></i>Publicações</a>
                                 <ul class="sub sub3">
									 <? if ($temPermissaoProcessoNova): ?>
                                    <li>
                                        <?php
                                            echo $this->Html->link('Nova', array(
                                                'controller' => 'Atendimento',
                                                'action' => 'index_processos_publicacao'
                                            ), array(
                                                'escape' => false
                                            ));
                                        ?>
                                    </li>
									<?php endif; ?>
									 <? if ($temPermissaoProcessoLista): ?>
                                    <li>
                                        <?php
                                        echo $this->Html->link('Lista', array(
                                         'controller' => 'Atendimento',
                                         'action' => 'indexPublicacao'
                                        ), array(
                                         'escape' => false
                                        ));
                                        ?>
                                    </li>
									<?php endif; ?>
                                 </ul>
                             </li>
                             
							  <?php endif; ?>

                          </ul>
                    </li>
                <?php endif; ?>
                <?php //if( ($tipoUsuario == USUARIO_INTERNO || $tipoUsuario == USUARIO_PERITO_SERVIDOR || $tipoUsuario == USUARIO_PERITO_CREDENCIADO) && Util::temPermissao("Menu.Relatorio")) : ?>
                <!-- <li class="sub-menu"> -->
                    <?php
                    // echo $this->Html->link('<i class="fa fa-files-o"></i>' .'Relatórios', array(
                    //     'controller' => 'Relatorio',
                    //     'action' => 'index'
                    // ), array(
                    //     'escape' => false
                    // ));
                    ?>
                <!-- </li> -->
                <? //endif; ?>


                <?php
                if( ($tipoUsuario == USUARIO_INTERNO || $tipoUsuario == USUARIO_PERITO_SERVIDOR || $tipoUsuario == USUARIO_PERITO_CREDENCIADO) && Util::temPermissao("Menu.Relatorio")) :
                    ?>
                    <li class="sub-menu"> 
                        <a href="#"><i class="fa fa-files-o"></i>Relatórios</a>
                        <ul class="sub">
                            <li class="sub-menu">
                                <?php
                                echo $this->Html->link("Por tipo", array(
                                    'controller' => 'Relatorio',
                                    'action' => 'index'
                                ));
                                ?>
                            </li>
                            <li class="sub-menu" >
                                <?php
                                echo $this->Html->link("Personalizado", array(
                                    'controller' => 'Relatorio',
                                    'action' => 'personalizado'
                                ));
                                ?>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>



            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->