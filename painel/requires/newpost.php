<form method="post" id="newpost" action="./assets/addpost.php">
    <div class="newpost">
        <div class="post-content">
            <div id="url" style="margin-bottom: 15px;">
                <div style="display: flex;">
                    <p style="line-height: 2;"><?php echo $url; ?></p>
                    <div style="margin-left: 5px;width: 100%;">
                        <input type="text" name="post_url" class="input-box" style="line-height: 1.5;" placeholder="Digite o novo url... (ex: nova-postagem)" required>
                    </div>
                </div>
            </div>
            <div id="titulo">
                <input type="text" name="title" class="input-box" style="padding: 5px 0.5em;" placeholder="Digite aqui o titulo..." required>
            </div>
            <div id="texto">
                <textarea id="editor"></textarea>
            </div>
        </div>
        <div class="postbox">
            <div id="postbox-submit">
                <button type="submit" class="sButtom preto">Publicar</button>
            </div>
        </div>
    </div>
</form>