<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
   xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
   <!-- Template to match the root element -->
   <xsl:template match="/questions">
      <xsl:for-each select="question">
         <!-- Display the question number and text -->
         <div class="w-screen max-w-6xl overflow-auto rounded-3xl border-2 border-gray-600">
            <legend class="text-sm font-semibold leading-6 text-white">
               Question 
               <xsl:value-of select="@id" />
               : 
               <xsl:value-of select="text" />
            </legend>
            <div class="mt-5 border-t border-gray-400">
               <div class="mt-2 space-y-2">
                  <!-- Display the answers as radio buttons -->
                  <xsl:for-each select="answer">
                     <div class="flex items-center gap-x-3">
                        <input type="checkbox" name="question_{@id}" 
                           value="{position()}" 
                           class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600" 
                           id="q{@id}_ans{position()}" />
                        <label for="q{@id}_ans{position()}" 
                           class="block text-sm font-medium leading-6 text-white">
                           <xsl:value-of select="." />
                        </label>
                     </div>
                     <br/>
                  </xsl:for-each>
               </div>
            </div>
         </div>
         <br/>
      </xsl:for-each>
   </xsl:template>
</xsl:stylesheet>
