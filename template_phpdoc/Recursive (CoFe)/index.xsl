<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output indent="yes" method="html" />
    <xsl:include href="layout.xsl" />

    <xsl:template match="/project" mode="contents">

        <div class="title">
            <h1>
                <xsl:value-of select="$title" disable-output-escaping="yes"/>
                <xsl:if test="$title = ''">phpDocumentor</xsl:if>
            </h1>
            <p>Documentation</p>
            <hr />
        </div>

        <div class="row">
            <div class="span7">
                <xsl:if test="count(/project/namespace[@name != 'default']) > 0">
                <div class="well">
                    <ul class="nav nav-list">
                        <li class="nav-header">Namespaces</li>
                        <xsl:apply-templates select="/project/namespace" mode="menu">
                            <xsl:sort select="@full_name" />
                        </xsl:apply-templates>
                    </ul>
                </div>
                </xsl:if>

                <xsl:if test="count(/project/package[@name != '' and @name != 'default']) > 0">
                <div class="well">
                    <ul class="nav nav-list">
                        <li class="nav-header">Packages</li>
                        <xsl:apply-templates select="/project/package" mode="menu">
                            <xsl:sort select="@name"/>
                        </xsl:apply-templates>
                    </ul>
                </div>
                </xsl:if>

            </div>
            <div class="span5">
                <div class="well">
                    <ul class="nav nav-list">
                        <li class="nav-header">Reports</li>
                        <xsl:apply-templates select="/" mode="report-overview" />
                    </ul>
                </div>
            </div>
        </div>
    </xsl:template>

</xsl:stylesheet>