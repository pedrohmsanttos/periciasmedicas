<div class="row">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <?= $this->Html->image($this->Html->url('/img/slide/Desert (Custom).jpg', false), array(), true); ?>
                <div class="carousel-caption">IMAGEM 1</div>
            </div>
            <div class="item">
                <?= $this->Html->image($this->Html->url('/img/slide/Lighthouse (Custom).jpg', false), array(), true); ?>
                <div class="carousel-caption">IMAGEM 2</div>
            </div>
            <div class="item">
                <?= $this->Html->image($this->Html->url('/img/slide/Penguins (Custom).jpg', false), array(), true); ?>
                <div class="carousel-caption">IMAGEM 3</div>
            </div>
        </div>
        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button"
           data-slide="prev">

            <span class="glyphicon glyphicon-chevron-left"></span>

        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button"
           data-slide="next">

            <span class="glyphicon glyphicon-chevron-right"></span>

        </a>
    </div>
    <div class="col-md-12">
        <div class="col-md-8 coluna1">
            <div class="col-md-12 coluna2">
                <div class="col-md-6 inf_1">
                    <?= $this->Html->image($this->Html->url('/img/portfolio-6.jpg', false), array(), true); ?>
                </div>
                <div class="col-md-6 inf_2">
                    <h3>Notícia em Destaque</h3>
                    <h5>Por Admin - Publicado em 27/08/13 às 16:31</h5>

                    <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica
                        e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor
                        desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro
                        de modelos de tipos.Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica
                        e de impressos.</p>
                    <button type="button" class="btn btn-primary btnNew">Leia Mais</button>
                </div>
            </div>
            <hr>
            <div class="col-md-12 coluna2 linha2">
                <hr>
                <h3>Instrumentais</h3>
                <a href="#"><p>Enquete - Na sua opinião, como está sendo realizado o trabalho social em seu município?</p></a>
                <h5>Disponível entre 10/07/14 a 15/07/14</h5>
                <a href="#"><p>Enquete - Na sua opinião, como está sendo realizado o trabalho social em seu município?</p></a>
                <h5>Disponível entre 10/07/14 a 15/07/14</h5>
                <a href="#"><p>Enquete - Na sua opinião, como está sendo realizado o trabalho social em seu município?</p></a>
                <h5>Disponível entre 10/07/14 a 15/07/14</h5>          
            </div>
            <div class="col-md-12 coluna2 news">            
                <div class="col-md-7 u_news">
                    <hr>
                    <h3>Últimas Notícias</h3>
                    <div class="col-md-4 g_news">
                        <a href="#">
                            <?= $this->Html->image($this->Html->url('/img/not_arquivo_1338507411.jpg', false), array(), true); ?>
                        </a>
                        <div class="title_g">
                            <h4>Notícia 1</h4>
                            <h6>Categoria 1 - Por Admin às 19:30</h6>
                            <h5>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</h5>
                        </div>
                    </div>
                    <div class="col-md-4">                
                        <a href="#">
                            <?= $this->Html->image($this->Html->url('/img/not_arquivo_1338507411.jpg', false), array(), true); ?>
                        </a>
                        <div class="title_g">
                            <h4>Notícia 1</h4>
                            <h6>Categoria 1 - Por Admin às 19:30</h6>
                            <h5>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a href="#"><p>+ Notícias</p></a>              
                        <a href="#">
                            <?= $this->Html->image($this->Html->url('/img/not_arquivo_1338507411.jpg', false), array(), true); ?>
                        </a>
                        <div class="title_g">

                            <h4>Notícia 1</h4>
                            <h6>Categoria 1 - Por Admin às 19:30</h6>
                            <h5>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</h5>
                        </div>         
                    </div>
                </div>
                <div class="col-md-5 galery">
                    <hr>
                    <h3>Galerias</h3>
                    <a href="#"><p>+ Galerias</p></a>
                    <a href="#">
                        <?= $this->Html->image($this->Html->url('/img/not_arquivo_1338507411.jpg', false), array('class' => 'img-thumbnail'), true); ?>
                    </a>
                    <div class="title_galery">
                        <h4>Galeria 1</h4>
                        <h6>Categoria 1 - Por Admin às 19:30</h6>
                        <h5>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</h5>
                    </div>                
                </div>                     
            </div>
            <div class="col-md-12 coluna2 institucional">              
                <hr>
                <h3>Institucional</h3>            
                <div class="col-md-6 institucional_l">
                    <h4>Texto 1</h4>
                    <p>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</p>
                    <button type="button" class="btn btn-primary btnInf">+ Informações</button>
                </div>
                <div class="col-md-6 institucional_r">
                    <h4>Texto 2</h4>
                    <p>Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração.</p>
                    <button type="button" class="btn btn-primary btnInf">+ Informações</button>
                </div>              
            </div>                                     
        </div>
        <div class="col-md-4 ">
            <div class="col-md-12">
                <div class="acesso">
                    <h3>Acesse o SIGAS</h3>
                    <a href="<?= Router::url('/Usuario/login'); ?>">
                        <?= $this->Html->image($this->Html->url('/img/acess.png', false), array(), true); ?>
                    </a>
                </div>
                <div class="calendar">
                    <?= $this->Html->image($this->Html->url('/img/calendar.png', false), array(), true); ?>
                </div>            
            </div>                             
        </div>                  
    </div>      
</div>