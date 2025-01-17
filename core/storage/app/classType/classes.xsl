<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" encoding="UTF-8" />

    <xsl:template match="/">
        <form action="/attendance" method="GET" class="space-y-6 flex flex-col items-center">
            <xsl:for-each select="form/field">
                <div class="w-1/2 mx-auto max-w-screen-md">
                    <label for="{name}" class="block text-lg font-semibold text-indigo-800">
                        <xsl:value-of select="label" />
                    </label>
                    
                    <xsl:choose>
                        <!-- Class Type validation: only alphabetic characters and spaces -->
                        <xsl:when test="name='class_type'">
                            <input type="text" name="{name}" id="{name}" 
                                   class="w-full px-5 py-2 border border-indigo-400 rounded-md text-lg text-black focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                   pattern="[a-zA-Z\s]+" 
                                   title="Please enter alphabetic characters only (e.g., Yoga, Pilates)"
                                   placeholder="Enter class type (e.g., Yoga, Pilates)"
                                   value="{value}" />
                        </xsl:when>

                        <!-- Classroom validation: must match 'A' followed by three digits (e.g., A101) -->
                        <xsl:when test="name='classroom'">
                            <input type="text" name="{name}" id="{name}"
                                   class="w-full px-5 py-2 border border-indigo-400 rounded-md text-lg text-black focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                   title="Please enter a classroom code starting with 'A' followed by three digits (e.g., A101)"
                                   placeholder="Enter classroom code (e.g., A101)"
                                   value="{value}" />
                        </xsl:when>

                        <!-- Time validation: use time input field for HH:mm format -->
                        <xsl:when test="name='time'">
                            <input type="time" name="{name}" id="{name}"
                                   class="w-full px-6 py-2 border border-indigo-400 rounded-md text-lg text-black focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                   placeholder="Enter time (HH:mm)"
                                   value="{value}" />
                        </xsl:when>

                        <!-- Default handling if no specific validation is defined -->
                        <xsl:otherwise>
                            <input type="text" name="{name}" id="{name}"
                                   class="w-full px-5 py-2 border border-indigo-400 rounded-md text-lg text-black focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                   placeholder="Enter value"
                                   value="{value}" />
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
            </xsl:for-each>

            <button type="submit"
                    class="w-3/4 mx-auto bg-red-500 text-white py-4 px-6 rounded-md text-lg font-semibold hover:bg-yellow-600 transition duration-300 max-w-screen-md">
                Filter Classes
            </button>
        </form>
    </xsl:template>
</xsl:stylesheet>
