<h2>Nordea Kontoutdrag</h2>
<p>
  <a href="<?=site_url('nordea/import')?>">Uppdatera</a><br />
  Kontobalans: <?=$balance?><br />
</p>
<form>
  <table id="posttable">
    <thead>
      <tr>
        <th>Datum</th>
        <th>Beskrivning</th>
        <th>Summa</th>
        <th>Verifikation</th>
      </tr>
    </thead>

    <tbody>
      <? $i = 0; ?>
      <?foreach($posts as $post) { ?>
      <tr class="<?=($i%2 ? 'even' : 'odd')?>">
        <td><?=$post->date?></td>
        <td><?=$post->description?></td>
        <td class="sum"><?=$post->sum?></td>
        <? if(empty($post->verification_id)) { ?>
        <td><a href="#">Skapa</a> <a href="#">Koppla</a></th>
        <? } else { ?>
        <td><a href="<?=site_url('accounting/verification/'.$post->verification_id)?>"><?=$post->verification_id?></a></th>
        <? } ?>
      </tr>
      <? $i++; ?>
      <? } ?>
    </tbody>
  </table>
</form>
