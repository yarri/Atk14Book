<header class="header--main">

  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand d-lg-none" href="/">ATK14 Book</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_toggle" aria-controls="navbar_toggle" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar_toggle">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="http://www.atk14.net/">ATK14 Framework</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="http://api.atk14.net/">API Reference</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="http://www.atk14sites.net/">Who uses ATK14?</a>
          </li>
          <li class="nav-separator"></li>
          {render partial="shared/langswitch_navbar"}
          <li class="nav-separator"></li>
          <li class="nav-item">
            <span class="nav-link">
              <div class="dark-mode-switch form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="js--darkmode-switch"{if $request->getCookieVar("dark_mode")} checked{/if}>
                <label class="form-check-label" for="js--darkmode-switch">{!"moon"|icon}</label>
              </div>
            </span>
          </li>
          <li class="nav-separator"></li>
        </ul>
        <form class="d-flex" role="search">
          <div class="input-group">
            <input class="form-control" type="search" placeholder="Search" aria-label="{t}Search{/t}"/>
            <button class="btn btn-outline-success" type="submit" aria-label="{t}Search{/t}">{!"magnifying-glass"|icon}</button>
          </div>
        </form>
      </div>
    </div>
  </nav>

  <div class="container-fluid header__title">
    <h1>ATK14 Book</h1>
    <p>{t}Read, hear, and study the ATK14 Book. Grow your skills. Relax. Repeat.{/t}</p>
  </div>

</header>