﻿// <auto-generated />
using ASPNET_WebAPI.Data;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Infrastructure;
using Microsoft.EntityFrameworkCore.Storage.ValueConversion;

#nullable disable

namespace ASPNET_WebAPI.Migrations
{
    [DbContext(typeof(ApplicationDbContext))]
    partial class ApplicationDbContextModelSnapshot : ModelSnapshot
    {
        protected override void BuildModel(ModelBuilder modelBuilder)
        {
#pragma warning disable 612, 618
            modelBuilder.HasAnnotation("ProductVersion", "8.0.8");

            modelBuilder.Entity("ASPNET_WebAPI.Models.TransactionDetails", b =>
                {
                    b.Property<string>("TransactionId")
                        .HasColumnType("TEXT");

                    b.Property<decimal>("Amount")
                        .HasColumnType("TEXT");

                    b.Property<string>("CancelUrl")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("Currency")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("CustomId")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("ItemName")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("ReferenceId")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("Status")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.Property<string>("SuccessUrl")
                        .IsRequired()
                        .HasColumnType("TEXT");

                    b.HasKey("TransactionId");

                    b.ToTable("TransactionDetails");
                });
#pragma warning restore 612, 618
        }
    }
}
