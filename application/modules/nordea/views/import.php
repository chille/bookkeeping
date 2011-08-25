<h2>Steg 1 - Logga in</h2>
<p>Ta fram din kortläsare och logga in på Nordea.</p>

<form action="<?=site_url('nordea/import')?>" method="post">
  <input type="hidden" name="challange" value="<?=$challange?>" />
  <table>
    <tr><td>Orgnr/personnr:</td><td><input type="text" name="orgnr" value="<?=$orgnr?>" /></td></tr>
    <tr><td>Kontrollkod:</td><td><strong><?=$challange?></strong></td></tr>
    <tr><td>Svarskod:</td><td><input type="text" name="response" /></td></tr>
    <tr><td colspan="2" align="right"><input type="submit" value="Hämta kontoutdrag" /></td></tr>
  </table>
</form>
